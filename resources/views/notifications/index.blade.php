@extends('layouts.app')

@section('content')

<div class="topbar">
    <div class="topbar-title">Notifications</div>
    <div class="topbar-right">
        <form method="POST" action="/notifications/read-all">
            @csrf
            <button type="submit" class="btn-secondary" style="font-size:12px">
                <i class="bi bi-check-all"></i> Mark all as read
            </button>
        </form>
        <div class="avatar sm">
            {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
        </div>
    </div>
</div>

<div class="page-content">
    <div class="table-card">

        @forelse($notifications as $notification)
        <div class="notification-row {{ $notification->IsRead ? '' : 'unread' }}">
            <div class="notification-icon">
                <i class="bi bi-bell{{ $notification->IsRead ? '' : '-fill' }}"
                   style="color:{{ $notification->IsRead ? '#aaa' : '#6C63FF' }}">
                </i>
            </div>
            <div class="notification-content">
                <div class="notification-message">
                    {{ $notification->Message }}
                </div>
                <div class="notification-time">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>
            @if(!$notification->IsRead)
            <form method="POST"
                  action="/notifications/{{ $notification->Id }}/read">
                @csrf
                <button type="submit" class="btn-secondary" style="font-size:11px;padding:4px 10px">
                    Mark read
                </button>
            </form>
            @else
            <span class="badge badge-gray">Read</span>
            @endif
        </div>
        @empty
        <div style="text-align:center;padding:40px">
            <i class="bi bi-bell-slash" style="font-size:32px;color:#ddd"></i>
            <p style="color:#aaa;margin-top:8px">No notifications yet.</p>
        </div>
        @endforelse

    </div>

    <div class="pagination-bar">
        {{ $notifications->links() }}
    </div>
</div>

@endsection