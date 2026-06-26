@extends('layouts.app')

@section('content')

<div class="topbar">
    <div style="display:flex;align-items:center;gap:10px">
        <a href="/users" style="color:#888;font-size:18px">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="topbar-title">User Profile</div>
    </div>
</div>

<div class="page-content">

    @if(session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="show-grid">
        <div>
            <div class="form-card" style="margin-bottom:16px">
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
                    <div class="comment-avatar"
                         style="width:56px;height:56px;font-size:18px;background:#6C63FF">
                        {{ strtoupper(substr($user->Name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:600;color:#1a1a2e">
                            {{ $user->Name }}
                        </div>
                        <div style="font-size:13px;color:#888">
                            {{ $user->Email }}
                        </div>
                    </div>
                    @if($user->IsActive)
                    <span class="badge badge-green" style="margin-left:auto">Active</span>
                    @else
                    <span class="badge badge-gray" style="margin-left:auto">Inactive</span>
                    @endif
                </div>

                <div class="detail-row">
                    <div class="detail-label">Role</div>
                    <div class="detail-value">
                        {{ $roles[$user->RoleId] ?? 'Unknown' }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Joined</div>
                    <div class="detail-value">
                        {{ $user->created_at->format('d M Y') }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Total Comments</div>
                    <div class="detail-value">{{ $totalComments }}</div>
                </div>
            </div>
            @if($user->Id !== auth()->user()->Id)
            <div class="form-card">
                <div class="form-section-title">Manage User</div>
                <form method="POST" action="/users/{{ $user->Id }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="RoleId" class="form-control">
                            <option value="1" {{ $user->RoleId == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user->RoleId == 2 ? 'selected' : '' }}>Agent</option>
                            <option value="3" {{ $user->RoleId == 3 ? 'selected' : '' }}>Employee</option>
                            <option value="4" {{ $user->RoleId == 4 ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="IsActive" class="form-control">
                            <option value="1" {{ $user->IsActive ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$user->IsActive ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-check"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
        <div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="bi bi-ticket"></i>
                    </div>
                    <div class="stat-label">Tickets Created</div>
                    <div class="stat-value">{{ $totalCreated }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-label">Resolved</div>
                    <div class="stat-value">{{ $resolved }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="stat-label">Assigned Tickets</div>
                    <div class="stat-value">{{ $totalAssigned }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon pink">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div class="stat-label">Open Tickets</div>
                    <div class="stat-value">{{ $openTickets }}</div>
                </div>
            </div>
            <div class="card" style="margin-bottom:16px">
                <div class="card-header">
                    <div class="card-title">Recent Tickets Created</div>
                </div>
                @forelse($recentTickets as $ticket)
                <div class="ticket-row">
                    <div>
                        <div class="ticket-ref">{{ $ticket->RefNumber }}</div>
                        <div class="ticket-name">{{ $ticket->Title }}</div>
                    </div>
                    @php
                        $sc = ['Open'=>'badge-purple','In Progress'=>'badge-orange','Pending'=>'badge-pink','Resolved'=>'badge-green','Closed'=>'badge-gray'];
                    @endphp
                    <span class="badge {{ $sc[$ticket->status->Name ?? ''] ?? 'badge-gray' }}">
                        {{ $ticket->status->Name ?? '-' }}
                    </span>
                </div>
                @empty
                <p class="empty-msg">No tickets created yet.</p>
                @endforelse
            </div>
            @if($totalAssigned > 0)
            <div class="card" style="margin-bottom:16px">
                <div class="card-header">
                    <div class="card-title">Assigned Tickets</div>
                </div>
                @foreach($assignedTickets as $ticket)
                <div class="ticket-row">
                    <div>
                        <div class="ticket-ref">{{ $ticket->RefNumber }}</div>
                        <div class="ticket-name">{{ $ticket->Title }}</div>
                    </div>
                    <span class="badge {{ $sc[$ticket->status->Name ?? ''] ?? 'badge-gray' }}">
                        {{ $ticket->status->Name ?? '-' }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent Activity</div>
                </div>
                @forelse($activityLogs as $log)
                <div class="ticket-row">
                    <div>
                        <div class="ticket-name" style="font-size:12px">
                            {{ $log->Action }}
                        </div>
                        <div class="ticket-ref">
                            {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="empty-msg">No activity yet.</p>
                @endforelse
            </div>

        </div>
    </div>
</div>

@endsection