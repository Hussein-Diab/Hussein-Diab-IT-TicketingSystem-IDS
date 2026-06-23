<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketComment;
use App\Models\Ticket;
use App\Helpers\NotificationHelper;

class CommentController extends Controller
{
    public function store(Request $request, $ticketId)
    {
        $request->validate([
            'Body' => 'required|max:1000',
        ]);

        TicketComment::create([
            'TicketId' => (int)$ticketId,
            'UserId'   => auth()->user()->Id,
            'Body'     => $request->Body,
        ]);

        $ticket = Ticket::findOrFail((int)$ticketId);

        if ($ticket->UserId !== auth()->user()->Id) {
            NotificationHelper::notifyEmployee(
                $ticket->UserId,
                auth()->user()->Name . ' commented on your ticket ' . $ticket->RefNumber . ': ' . $ticket->Title
            );
        }

        if ($ticket->AssignedTo && $ticket->AssignedTo !== auth()->user()->Id) {
            NotificationHelper::notifyAgent(
                $ticket->AssignedTo,
                auth()->user()->Name . ' commented on ticket ' . $ticket->RefNumber . ': ' . $ticket->Title
            );
        }

        return redirect('/tickets/' . $ticketId)
            ->with('success', 'Comment added successfully!');
    }
}