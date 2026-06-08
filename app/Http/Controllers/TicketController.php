<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with([
            'category',
            'priority',
            'status',
            'user'
        ])->latest()->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::all();
        $priorities  = Priority::all();
        return view('tickets.create', compact(
            'categories',
            'priorities'
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

        $refNumber = 'TKT-' . strtoupper(Str::random(6));
        Ticket::create([
            'RefNumber'   => $refNumber,
            'Title'       => $request->Title,
            'Description' => $request->Description,
            'CategoryId'  => $request->CategoryId,
            'PriorityId'  => $request->PriorityId,
            'StatusId'    => 1,
            'UserId'      => Auth::id(),
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
            'user'
        ])->findOrFail($id);

        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket     = Ticket::findOrFail($id);
        $categories = Category::all();
        $priorities  = Priority::all();
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
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect('/tickets')
            ->with('success', 'Ticket deleted successfully!');
    }
}
