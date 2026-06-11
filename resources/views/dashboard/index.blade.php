@extends('layouts.app')

@section('content')

<div class="topbar">
    <div class="topbar-title">Dashboard</div>
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

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-ticket"></i>
            </div>
            <div class="stat-label">Open Tickets</div>
            <div class="stat-value">{{ $openTickets }}</div>
            <div class="stat-sub purple">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div class="stat-label">In Progress</div>
            <div class="stat-value">{{ $inProgressTickets }}</div>
            <div class="stat-sub orange">Assigned</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pink">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $pendingTickets }}</div>
            <div class="stat-sub pink">Awaiting</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Resolved</div>
            <div class="stat-value">{{ $resolvedTickets }}</div>
            <div class="stat-sub green">Done</div>
        </div>
    </div>

    <div class="bottom-grid">

        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Tickets</div>
                <a href="/tickets" class="card-link">View all</a>
            </div>
            @forelse($recentTickets as $ticket)
            <div class="ticket-row">
                <div>
                    <div class="ticket-ref">{{ $ticket->RefNumber }}</div>
                    <div class="ticket-name">{{ $ticket->Title }}</div>
                </div>
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
            </div>
            @empty
            <p class="empty-msg">No tickets yet.</p>
            @endforelse
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Tickets by Category</div>
            </div>
            @foreach($ticketsByCategory as $cat)
            <div class="bar-row">
                <div class="bar-label">{{ $cat->Name }}</div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ $totalTickets > 0 ? ($cat->tickets_count / $totalTickets) * 100 : 0 }}%"></div>
                </div>
                <div class="bar-count">{{ $cat->tickets_count }}</div>
            </div>
            @endforeach
        </div>

    </div>
</div>

@endsection