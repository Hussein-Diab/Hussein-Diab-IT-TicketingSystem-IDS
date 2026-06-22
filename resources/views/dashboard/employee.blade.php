@extends('layouts.app')

@section('content')

<div class="topbar">
    <div class="topbar-title">My Dashboard</div>
    <div class="topbar-right">
        <div class="notif-btn">
            <i class="bi bi-bell"></i>
            <div class="notif-dot"></div>
        </div>
        <div class="avatar sm">
            {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
        </div>
    </div>
</div>

<div class="page-content">

    {{-- Welcome message --}}
    <div class="form-card" style="margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:14px">
            <div class="avatar" style="width:48px;height:48px;font-size:16px;background:#6C63FF">
                {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:16px;font-weight:600;color:#1a1a2e">
                    Welcome back, {{ auth()->user()->Name }}!
                </div>
                <div style="font-size:13px;color:#888;margin-top:2px">
                    Here's a summary of your tickets
                </div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-ticket"></i>
            </div>
            <div class="stat-label">My Total Tickets</div>
            <div class="stat-value">{{ $totalTickets }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div class="stat-label">Open</div>
            <div class="stat-value">{{ $openTickets }}</div>
            <div class="stat-sub purple">Needs attention</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div class="stat-label">In Progress</div>
            <div class="stat-value">{{ $inProgressTickets }}</div>
            <div class="stat-sub orange">Being worked on</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Resolved</div>
            <div class="stat-value">{{ $resolvedTickets }}</div>
            <div class="stat-sub green">Completed</div>
        </div>
    </div>

    <div class="card" style="margin-top:16px">
        <div class="card-header">
            <div class="card-title">My Recent Tickets</div>
            <a href="/tickets" class="card-link">View all</a>
        </div>

        @forelse($recentTickets as $ticket)
        <div class="ticket-row">
            <div>
                <div class="ticket-ref">{{ $ticket->RefNumber }}</div>
                <div class="ticket-name">{{ $ticket->Title }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:8px">
                @php
                    $statusColors = [
                        'Open'        => 'badge-purple',
                        'In Progress' => 'badge-orange',
                        'Pending'     => 'badge-pink',
                        'Resolved'    => 'badge-green',
                        'Closed'      => 'badge-gray',
                    ];
                    $color = $statusColors[$ticket->status->Name] ?? 'badge-gray';
                @endphp
                <span class="badge {{ $color }}">
                    {{ $ticket->status->Name }}
                </span>
                <a href="/tickets/{{ $ticket->Id }}"
                   class="action-btn view">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px">
            <i class="bi bi-ticket" 
               style="font-size:32px;color:#ddd"></i>
            <p style="color:#aaa;margin-top:8px;font-size:13px">
                You haven't submitted any tickets yet.
            </p>
            <a href="/tickets/create" 
               class="btn-primary"
               style="margin-top:12px;display:inline-flex">
                <i class="bi bi-plus"></i> Create your first ticket
            </a>
        </div>
        @endforelse
    </div>

    <div class="card" style="margin-top:16px">
        <div class="card-title" style="margin-bottom:12px">
            Quick Actions
        </div>
        <div style="display:flex;gap:10px">
            <a href="/tickets/create" class="btn-primary">
                <i class="bi bi-plus-circle"></i> New Ticket
            </a>
            <a href="/tickets" class="btn-secondary">
                <i class="bi bi-list"></i> View My Tickets
            </a>
        </div>
    </div>

</div>

@endsection