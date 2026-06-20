<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->isAdminOrManager()) {
        $totalTickets=Ticket::count();
        $openTickets=Ticket::where('StatusId', 1)->count();
        $inProgressTickets=Ticket::where('StatusId', 2)->count();
        $pendingTickets=Ticket::where('StatusId', 3)->count();
        $resolvedTickets=Ticket::where('StatusId', 4)->count();
        $recentTickets=Ticket::with([
            'category', 'priority', 'status', 'user'
        ])->latest()->take(5)->get();

    } else {
        
        $totalTickets=Ticket::where('UserId', $user->Id)->count();
        $openTickets =Ticket::where('UserId', $user->Id)->where('StatusId', 1)->count();
        $inProgressTickets=Ticket::where('UserId', $user->Id)->where('StatusId', 2)->count();
        $pendingTickets= Ticket::where('UserId', $user->Id)->where('StatusId', 3)->count();
        $resolvedTickets=Ticket::where('UserId', $user->Id)->where('StatusId', 4)->count();
        $recentTickets=Ticket::with([
            'category', 'priority', 'status', 'user'
        ])->where('UserId', $user->Id)->latest()->take(5)->get();
    }

    $ticketsByCategory = Category::withCount('tickets')->get();

    return view('dashboard.index', compact(
        'totalTickets',
        'openTickets',
        'inProgressTickets',
        'pendingTickets',
        'resolvedTickets',
        'recentTickets',
        'ticketsByCategory'
    ));
}
}
