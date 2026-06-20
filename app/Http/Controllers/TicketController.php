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
            $employees = User::where('RoleId', 3)->get(); // Get all employees
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

        $refNumber = 'TKT-' . str_pad(
            Ticket::count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
        $userId = auth()->user()->isAdminOrManager() && $request->UserId
            ? $request->UserId
            : auth()->user()->Id;

        Ticket::create([
            'RefNumber'   => $refNumber,
            'Title'       => $request->Title,
            'Description' => $request->Description,
            'CategoryId'  => $request->CategoryId,
            'PriorityId'  => $request->PriorityId,
            'StatusId'    => 1,
            'UserId'      => $userId,
            'AssignedTo'  => $request->AssignedTo ?? null,
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

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'Title'       => $request->Title,
            'Description' => $request->Description,
            'CategoryId'  => $request->CategoryId,
            'PriorityId'  => $request->PriorityId,
            'StatusId'    => $request->StatusId,
        ]);

        return redirect('/tickets')
            ->with('success', 'Ticket updated successfully!');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail((int)$id);
        $ticket->delete();

        return redirect('/tickets')
            ->with('success', 'Ticket deleted successfully!');
    }
}
