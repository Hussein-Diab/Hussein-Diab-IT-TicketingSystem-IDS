<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketComment;

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

        return redirect('/tickets/' . $ticketId)
            ->with('success', 'Comment added successfully!');
    }
}