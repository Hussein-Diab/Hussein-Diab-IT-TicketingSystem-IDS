<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\User;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdminOrManager()) {
            $tickets = Ticket::with(['category', 'priority', 'status', 'user'])->latest()->paginate(10);
        } elseif ($user->isAgent()) {
            $tickets = Ticket::with(['category', 'priority', 'status', 'user'])
                ->where('AssignedTo', $user->Id)
                ->latest()
                ->paginate(10);
        } else {
            $tickets = Ticket::with(['category', 'priority', 'status', 'user'])
                ->where('UserId', $user->Id)
                ->latest()
                ->paginate(10);
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::all();
        $priorities = Priority::all();
        $employees  = null;

        if (auth()->user()->isAdminOrManager()) {
            $employees = User::where('RoleId', 3)->get();
        }

        return view('tickets.create', compact('categories', 'priorities', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Title'         => 'required|max:255',
            'Description'   => 'required',
            'CategoryId'    => 'required|exists:Categories,Id',
            'PriorityId'    => 'required|exists:Priorities,Id',
            'attachments.*' => 'nullable|file|max:5120|mimes:png,jpg,jpeg,pdf,doc,docx',
        ]);

        $refNumber = 'TKT-' . strtoupper(uniqid());
        $userId = auth()->user()->isAdminOrManager() && $request->UserId ? $request->UserId : auth()->user()->Id;

        $ticket = Ticket::create([
            'RefNumber'   => $refNumber,
            'Title'       => $request->Title,
            'Description' => $request->Description,
            'CategoryId'  => $request->CategoryId,
            'PriorityId'  => $request->PriorityId,
            'StatusId'    => 1,
            'UserId'      => $userId,
            'AssignedTo'  => $request->AssignedTo ?? null,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {

                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('attachments', $fileName, 'public');
                TicketAttachment::create([
                    'TicketId' => $ticket->Id,
                    'FileName' => $file->getClientOriginalName(),
                    'FilePath' => $filePath,
                    'FileSize' => $file->getSize(),
                ]);
            }
        }

        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => $ticket->Id,
            'Action'     => 'Ticket ' . $refNumber . ' was created by ' . auth()->user()->Name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        NotificationHelper::notifyAdminsAndManagers(
            'New ticket ' . $refNumber . ' was submitted by ' . auth()->user()->Name . ': ' . $request->Title
        );

        if ($request->AssignedTo) {
            NotificationHelper::notifyAgent(
                $request->AssignedTo,
                'You have been assigned ticket ' . $refNumber . ': ' . $request->Title
            );
        }

        return redirect('/tickets')->with('success', 'Ticket created successfully!');
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'category',
            'priority',
            'status',
            'user',
            'comments.user',
            'attachments'
        ])->findOrFail((int)$id);

        $user = auth()->user();
        if (!$this->canAccessTicket($user, $ticket)) {
            return redirect('/tickets')
                ->withErrors(['error' => 'You do not have permission to view this ticket.']);
        }
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail((int)$id);
        $user = auth()->user();
        if (!$this->canAccessTicket($user, $ticket)) {
            return redirect('/tickets')
                ->withErrors(['error' => 'You do not have permission to edit this ticket.']);
        }
        $categories = Category::all();
        $priorities = Priority::all();
        $statuses   = Status::all();
        return view('tickets.edit', compact(
            'ticket',
            'categories',
            'priorities',
            'statuses'
        ));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail((int)$id);
        $user   = auth()->user();
        if (!$this->canAccessTicket($user, $ticket)) {
            return redirect('/tickets')
                ->withErrors(['error' => 'You do not have permission to update this ticket.']);
        }
        $request->validate([
            'Title'       => 'required|max:255',
            'Description' => 'required',
            'CategoryId'  => 'required|exists:Categories,Id',
            'PriorityId'  => 'required|exists:Priorities,Id',
            'StatusId'    => 'required|exists:Statuses,Id',
        ]);

        $ticket = Ticket::findOrFail((int)$id);
        $oldStatus = $ticket->StatusId;
        $oldPriority = $ticket->PriorityId;
        $oldAssigned = $ticket->AssignedTo;

        $ticket->update([
            'Title'       => $request->Title,
            'Description' => $request->Description,
            'CategoryId'  => $request->CategoryId,
            'PriorityId'  => $request->PriorityId,
            'StatusId'    => $request->StatusId,
            'AssignedTo'  => $request->AssignedTo ?? null,
        ]);

        $action = 'Ticket ' . $ticket->RefNumber . ' was updated by ' . auth()->user()->Name;

        if ($oldStatus != $request->StatusId) {
            $oldStatusName = DB::table('Statuses')->where('Id', $oldStatus)->value('Name');
            $newStatusName = DB::table('Statuses')->where('Id', $request->StatusId)->value('Name');
            $action .= ' — Status changed from ' . $oldStatusName . ' to ' . $newStatusName;

            NotificationHelper::notifyEmployee(
                $ticket->UserId,
                'Your ticket ' . $ticket->RefNumber . ' status changed from ' . $oldStatusName . ' to ' . $newStatusName
            );
        }

        if ($oldPriority != $request->PriorityId) {
            $oldPriorityName = DB::table('Priorities')->where('Id', $oldPriority)->value('Name');
            $newPriorityName = DB::table('Priorities')->where('Id', $request->PriorityId)->value('Name');
            $action .= ' — Priority changed from ' . $oldPriorityName . ' to ' . $newPriorityName;
        }

        if ($request->AssignedTo && $oldAssigned != $request->AssignedTo) {
            NotificationHelper::notifyAgent(
                $request->AssignedTo,
                'You have been assigned ticket ' . $ticket->RefNumber . ': ' . $ticket->Title
            );
        }

        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => $ticket->Id,
            'Action'     => $action,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/tickets')->with('success', 'Ticket updated successfully!');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail((int)$id);
        $user   = auth()->user();
        $refNumber = $ticket->RefNumber;
        if (!$user->isAdminOrManager()) {
            return redirect('/tickets')
                ->withErrors(['error' => 'You do not have permission to delete tickets.']);
        }
        DB::table('ActivityLogs')->where('TicketId', $ticket->Id)->delete();
        DB::table('TicketComments')->where('TicketId', $ticket->Id)->delete();
        DB::table('TicketAttachments')->where('TicketId', $ticket->Id)->delete();

        $ticket->delete();

        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => null,
            'Action'     => 'Ticket ' . $refNumber . ' was deleted by ' . auth()->user()->Name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/tickets')->with('success', 'Ticket deleted successfully!');
    }
    private function canAccessTicket($user, $ticket)
    {
        if ($user->isAdminOrManager()) {
            return true;
        }
        if ($user->isAgent()) {
            return $ticket->AssignedTo == $user->Id;
        }
        return $ticket->UserId == $user->Id;
    }
}
