<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdminOrManager()) {
            return $this->adminDashboard();
        } elseif ($user->isAgent()) {
            return $this->agentDashboard($user);
        } else {
            return $this->employeeDashboard($user);
        }
    }

    private function adminDashboard()
    {
        $totalTickets      = Ticket::count();
        $openTickets       = Ticket::where('StatusId', 1)->count();
        $inProgressTickets = Ticket::where('StatusId', 2)->count();
        $pendingTickets    = Ticket::where('StatusId', 3)->count();
        $resolvedTickets   = Ticket::where('StatusId', 4)->count();
        $closedTickets     = Ticket::where('StatusId', 5)->count();

        $recentTickets = Ticket::with([
            'category',
            'priority',
            'status',
            'user'
        ])->latest()->take(5)->get();

        $ticketsByCategory = Category::withCount('tickets')->get();
        $ticketsByPriority = DB::table('Tickets')
            ->join('Priorities', 'Tickets.PriorityId', '=', 'Priorities.Id')
            ->select('Priorities.Name', DB::raw('count(*) as total'))
            ->groupBy('Priorities.Name')
            ->get();
        $ticketsByStatus = DB::table('Tickets')
            ->join('Statuses', 'Tickets.StatusId', '=', 'Statuses.Id')
            ->select('Statuses.Name', DB::raw('count(*) as total'))
            ->groupBy('Statuses.Name')->get();
        $ticketsPerMonth = DB::table('Tickets')
            ->select(
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('MONTHNAME(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))->get();

        return view('dashboard.admin', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'pendingTickets',
            'resolvedTickets',
            'closedTickets',
            'recentTickets',
            'ticketsByCategory',
            'ticketsByPriority',
            'ticketsPerMonth',
            'ticketsByStatus'
        ));
    }

    private function agentDashboard($user)
    {
        $totalTickets = Ticket::where('AssignedTo', $user->Id)->count();
        $openTickets  = Ticket::where('AssignedTo', $user->Id)->where('StatusId', 1)->count();
        $inProgressTickets = Ticket::where('AssignedTo', $user->Id)->where('StatusId', 2)->count();
        $pendingTickets = Ticket::where('AssignedTo', $user->Id)->where('StatusId', 3)->count();
        $resolvedTickets = Ticket::where('AssignedTo', $user->Id)->where('StatusId', 4)->count();

        $recentTickets = Ticket::with([
            'category',
            'priority',
            'status',
            'user'
        ])
            ->where('AssignedTo', $user->Id)
            ->latest()
            ->take(5)
            ->get();

        $ticketsByCategory = Category::withCount('tickets')->get();

        return view('dashboard.employee', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'pendingTickets',
            'resolvedTickets',
            'recentTickets',
            'ticketsByCategory'
        ));
    }

    private function employeeDashboard($user)
    {
        $totalTickets      = Ticket::where('UserId', $user->Id)->count();
        $openTickets       = Ticket::where('UserId', $user->Id)->where('StatusId', 1)->count();
        $inProgressTickets = Ticket::where('UserId', $user->Id)->where('StatusId', 2)->count();
        $pendingTickets    = Ticket::where('UserId', $user->Id)->where('StatusId', 3)->count();
        $resolvedTickets   = Ticket::where('UserId', $user->Id)->where('StatusId', 4)->count();

        $recentTickets = Ticket::with([
            'category',
            'priority',
            'status'
        ])
            ->where('UserId', $user->Id)
            ->latest()
            ->take(5)
            ->get();

        $ticketsByCategory = Category::withCount('tickets')->get();

        return view('dashboard.employee', compact(
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
