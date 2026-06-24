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
            <div class="stat-label">Total Tickets</div>
            <div class="stat-value">{{ $totalTickets }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-folder2-open"></i>
            </div>
            <div class="stat-label">Open</div>
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
            <div class="stat-sub pink">Waiting</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Resolved</div>
            <div class="stat-value">{{ $resolvedTickets }}</div>
            <div class="stat-sub green">Done</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0f0f4">
                <i class="bi bi-x-circle" style="color:#888"></i>
            </div>
            <div class="stat-label">Closed</div>
            <div class="stat-value">{{ $closedTickets }}</div>
            <div class="stat-sub" style="color:#888">Closed</div>
        </div>
    </div>

    <div class="charts-grid" style="margin-top:16px">

        <div class="card">
            <div class="card-header">
                <div class="card-title">Tickets Over Last 6 Months</div>
            </div>
            <canvas id="lineChart" height="120"></canvas>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tickets by Status</div>
            </div>
            <canvas id="statusChart" height="120"></canvas>
        </div>

    </div>


    <div class="charts-grid" style="margin-top:16px">


        <div class="card">
            <div class="card-header">
                <div class="card-title">Tickets by Category</div>
            </div>
            <canvas id="categoryChart" height="120"></canvas>
        </div>


        <div class="card">
            <div class="card-header">
                <div class="card-title">Tickets by Priority</div>
            </div>
            <canvas id="priorityChart" height="120"></canvas>
        </div>

    </div>


    <div class="bottom-grid" style="margin-top:16px">


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
                    <div class="bar-fill"
                         style="width: {{ $totalTickets > 0 ? ($cat->tickets_count / $totalTickets) * 100 : 0 }}%">
                    </div>
                </div>
                <div class="bar-count">{{ $cat->tickets_count }}</div>
            </div>
            @endforeach
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const lineCtx = document.getElementById('lineChart').getContext('2d');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($ticketsPerMonth->pluck('month')) !!},
        datasets: [{
            label: 'Tickets',
            data: {!! json_encode($ticketsPerMonth->pluck('total')) !!},
            borderColor: '#6C63FF',
            backgroundColor: 'rgba(108, 99, 255, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#6C63FF',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($ticketsByStatus->pluck('Name')) !!},
        datasets: [{
            data: {!! json_encode($ticketsByStatus->pluck('total')) !!},
            backgroundColor: [
                '#6C63FF',
                '#e67e22',
                '#e91e63',
                '#2e7d32',
                '#aaaaaa',
            ],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($ticketsByCategory->pluck('Name')) !!},
        datasets: [{
            label: 'Tickets',
            data: {!! json_encode($ticketsByCategory->pluck('tickets_count')) !!},
            backgroundColor: 'rgba(108, 99, 255, 0.8)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

const priorityCtx = document.getElementById('priorityChart').getContext('2d');
new Chart(priorityCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($ticketsByPriority->pluck('Name')) !!},
        datasets: [{
            data: {!! json_encode($ticketsByPriority->pluck('total')) !!},
            backgroundColor: [
                '#2e7d32',
                '#6C63FF',
                '#e67e22',
                '#c62828',
            ],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

@endsection