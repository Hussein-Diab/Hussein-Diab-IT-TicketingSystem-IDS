@extends('layouts.app')

@section('content')

{{-- Topbar --}}
<div class="topbar">
    <div class="topbar-title">All Tickets</div>
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

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-title">Tickets</div>
        <a href="/tickets/create" class="btn-primary">
            <i class="bi bi-plus"></i> New Ticket
        </a>
    </div>

    {{-- Filters --}}
    <div class="filters-bar">
        <div class="search-box">
            <i class="bi bi-search" style="color:#aaa"></i>
            <input type="text" 
                   placeholder="Search tickets..." 
                   id="searchInput">
        </div>
        <select class="filter-select" id="statusFilter">
            <option value="">All statuses</option>
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Pending">Pending</option>
            <option value="Resolved">Resolved</option>
            <option value="Closed">Closed</option>
        </select>
        <select class="filter-select" id="priorityFilter">
            <option value="">All priorities</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
            <option value="Critical">Critical</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <table id="ticketsTable">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td class="ticket-id-col">
                        {{ $ticket->RefNumber }}
                    </td>
                    <td>{{ $ticket->Title }}</td>
                    <td>{{ $ticket->category->Name ?? '-' }}</td>
                    <td>
                        @php
                            $priorityColors = [
                                'Low'      => 'badge-green',
                                'Medium'   => 'badge-purple',
                                'High'     => 'badge-orange',
                                'Critical' => 'badge-red',
                            ];
                            $pc = $priorityColors[$ticket->priority->Name ?? ''] ?? 'badge-gray';
                        @endphp
                        <span class="badge {{ $pc }}">
                            {{ $ticket->priority->Name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'Open'        => 'badge-purple',
                                'In Progress' => 'badge-orange',
                                'Pending'     => 'badge-pink',
                                'Resolved'    => 'badge-green',
                                'Closed'      => 'badge-gray',
                            ];
                            $sc = $statusColors[$ticket->status->Name ?? ''] ?? 'badge-gray';
                        @endphp
                        <span class="badge {{ $sc }}">
                            {{ $ticket->status->Name ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="/tickets/{{ $ticket->Id }}" 
                               class="action-btn view"
                               title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/tickets/{{ $ticket->Id }}/edit" 
                               class="action-btn edit"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" 
                                  action="/tickets/{{ $ticket->Id }}"
                                  onsubmit="return confirm('Delete this ticket?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="action-btn delete"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#aaa;padding:30px">
                        No tickets found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination-bar">
            <div class="page-info">
                Showing {{ $tickets->firstItem() }} 
                to {{ $tickets->lastItem() }} 
                of {{ $tickets->total() }} tickets
            </div>
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<script>
// Simple search filter
document.getElementById('searchInput').addEventListener('keyup', function() {
    let search = this.value.toLowerCase();
    let rows = document.querySelectorAll('#ticketsTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(search) ? '' : 'none';
    });
});
</script>

@endsection