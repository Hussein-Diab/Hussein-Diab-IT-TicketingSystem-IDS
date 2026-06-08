<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpDesk IDS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

@auth
<div class="app-layout">
    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="bi bi-headset"></i>
            HelpDesk IDS
        </div>
        <div class="nav-section">
            <a href="/dashboard"
               class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <a href="/tickets"
               class="nav-item {{ request()->is('tickets') ? 'active' : '' }}">
                <i class="bi bi-ticket"></i> All Tickets
            </a>
            <a href="/tickets/create"
               class="nav-item {{ request()->is('tickets/create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> New Ticket
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-bar-chart"></i> Reports
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-bell"></i> Notifications
            </a>
        </div>
        <div class="sidebar-bottom">
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
            </div>
            <div class="sidebar-user">
                <div class="sidebar-username">{{ auth()->user()->Name }}</div>
                <div class="sidebar-role">
                    @php
                        $roles = [1=>'Admin',2=>'Agent',3=>'Employee',4=>'Manager'];
                        echo $roles[auth()->user()->RoleId] ?? 'User';
                    @endphp
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</div>

@else
@yield('content')
@endauth

</body>
</html>