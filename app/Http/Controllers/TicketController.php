<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdminOrManager()) {
            $tickets = Ticket::with([
                'category',
                'priority',
                'status',
                'user'
            ])->latest()->paginate(10);
        } elseif ($user->isAgent()) {
            $tickets = Ticket::with([
                'category',
                'priority',
                'status',
                'user'
            ])
                ->where('AssignedTo', $user->Id)
                ->latest()
                ->paginate(10);
        } else {
            $tickets = Ticket::with([
                'category',
                'priority',
                'status',
                'user'
            ])
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

        return view('tickets.create', compact(
            'categories',
            'priorities',
            'employees'
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'Title'       => 'required|max:255',
            'Description' => 'required',
            'CategoryId'  => 'required|exists:Categories,Id',
            'PriorityId'  => 'required|exists:Priorities,Id',
        ]);

        $refNumber = 'TKT-' . strtoupper(uniqid());

        $userId = auth()->user()->isAdminOrManager() && $request->UserId
            ? $request->UserId
            : auth()->user()->Id;


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

        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => $ticket->Id,
            'Action'     => 'Ticket ' . $refNumber . ' was created by ' . auth()->user()->Name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/tickets')
            ->with('success', 'Ticket created successfully!');
    }
    public function show($id)
    {
        $ticket = Ticket::with([
            'category',
            'priority',
            'status',
            'user',
            'comments.user'
        ])->findOrFail((int)$id);

        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail((int)$id);

        if (
            auth()->user()->isEmployee() &&
            $ticket->UserId !== auth()->user()->Id
        ) {
            return redirect('/tickets')
                ->withErrors(['error' => 'You can only edit your own tickets.']);
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
        $request->validate([
            'Title'       => 'required|max:255',
            'Description' => 'required',
            'CategoryId'  => 'required',
            'PriorityId'  => 'required',
            'StatusId'    => 'required',
        ]);

        $ticket = Ticket::findOrFail((int)$id);
        $oldStatus   = $ticket->StatusId;
        $oldPriority = $ticket->PriorityId;

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
        }

        if ($oldPriority != $request->PriorityId) {
            $oldPriorityName = DB::table('Priorities')->where('Id', $oldPriority)->value('Name');
            $newPriorityName = DB::table('Priorities')->where('Id', $request->PriorityId)->value('Name');
            $action .= ' — Priority changed from ' . $oldPriorityName . ' to ' . $newPriorityName;
        }

        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => $ticket->Id,
            'Action'     => $action,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/tickets')
            ->with('success', 'Ticket updated successfully!');
    }
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail((int)$id);
        DB::table('ActivityLogs')->insert([
            'UserId'     => auth()->user()->Id,
            'TicketId'   => $ticket->Id,
            'Action'     => 'Ticket ' . $ticket->RefNumber . ' was deleted by ' . auth()->user()->Name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('ActivityLogs')->where('TicketId', $ticket->Id)->delete();
        DB::table('TicketComments')->where('TicketId', $ticket->Id)->delete();
        DB::table('TicketAttachments')->where('TicketId', $ticket->Id)->delete();
        $ticket->delete();

        return redirect('/tickets')
            ->with('success', 'Ticket deleted successfully!');
    }
}
