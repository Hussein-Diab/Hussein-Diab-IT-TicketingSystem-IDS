<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdminOrManager()) {
            return redirect('/dashboard');
        }
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('StatusId', 1)->count();
        $inProgressTickets = Ticket::where('StatusId', 2)->count();
        $pendingTickets = Ticket::where('StatusId', 3)->count();
        $resolvedTickets = Ticket::where('StatusId', 4)->count();
        $closedTickets = Ticket::where('StatusId', 5)->count();
        $ticketsByCategory = DB::table('Tickets')->join('Categories', 'Tickets.CategoryId', '=', 'Categories.Id')->select('Categories.Name', DB::raw('count(*) as total'))->groupBy('Categories.Name')->get();
        $ticketsByPriority = DB::table('Tickets')->join('Priorities', 'Tickets.PriorityId', '=', 'Priorities.Id')->select('Priorities.Name', DB::raw('count(*) as total'))->groupBy('Priorities.Name')->get();
        $ticketsPerMonth = DB::table('Tickets')->select(
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(
                DB::raw('MONTHNAME(created_at)'),
                DB::raw('MONTH(created_at)')
            )->orderBy(DB::raw('MONTH(created_at)'))->get();
        $agentPerformance = DB::table('Users')->where('RoleId', 2)->select('Users.Id', 'Users.Name')->get()
            ->map(function ($agent) {
                $agent->totalAssigned = Ticket::where('AssignedTo', $agent->Id)->count();
                $agent->resolved = Ticket::where('AssignedTo', $agent->Id)->where('StatusId', 4)->count();
                $agent->inProgress = Ticket::where('AssignedTo', $agent->Id)->where('StatusId', 2)->count();
                return $agent;
            });
        $activeEmployees = DB::table('Tickets')->join('Users', 'Tickets.UserId', '=', 'Users.Id')->select('Users.Name', DB::raw('count(*) as total'))->groupBy('Users.Name')->orderByDesc('total')->take(5)->get();
        return view('reports.index', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'pendingTickets',
            'resolvedTickets',
            'closedTickets',
            'ticketsByCategory',
            'ticketsByPriority',
            'ticketsPerMonth',
            'agentPerformance',
            'activeEmployees'
        ));
    }
}
