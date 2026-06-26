@extends('layouts.app')

@section('content')

<div class="topbar">
    <div class="topbar-title">Users</div>
    <div class="topbar-right">
        <div class="avatar sm">
            {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
        </div>
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

    <div class="page-header">
        <div class="page-title">All Users</div>
    </div>
    <form method="GET" action="/users">
        <div class="filters-bar">
            <div class="search-box">
                <i class="bi bi-search" style="color:#aaa"></i>
                <input type="text"
                       name="search"
                       placeholder="Search by name or email..."
                       value="{{ request('search') }}">
            </div>
            <select name="role" class="filter-select">
                <option value="">All roles</option>
                <option value="1" {{ request('role') == 1 ? 'selected' : '' }}>Admin</option>
                <option value="2" {{ request('role') == 2 ? 'selected' : '' }}>Agent</option>
                <option value="3" {{ request('role') == 3 ? 'selected' : '' }}>Employee</option>
                <option value="4" {{ request('role') == 4 ? 'selected' : '' }}>Manager</option>
            </select>
            <select name="status" class="filter-select">
                <option value="">All statuses</option>
                <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            <a href="/users" class="btn-secondary">Clear</a>
        </div>
    </form>
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="comment-avatar" style="width:32px;height:32px;font-size:11px">
                                {{ strtoupper(substr($user->Name, 0, 2)) }}
                            </div>
                            {{ $user->Name }}
                            @if($user->Id === auth()->user()->Id)
                            <span class="badge badge-purple" style="font-size:10px">You</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $user->Email }}</td>
                    <td>
                        @php
                            $roleColors = [
                                1 => 'badge-red',
                                2 => 'badge-orange',
                                3 => 'badge-purple',
                                4 => 'badge-green',
                            ];
                            $roleNames = [
                                1 => 'Admin',
                                2 => 'Agent',
                                3 => 'Employee',
                                4 => 'Manager',
                            ];
                        @endphp
                        <span class="badge {{ $roleColors[$user->RoleId] ?? 'badge-gray' }}">
                            {{ $roleNames[$user->RoleId] ?? 'Unknown' }}
                        </span>
                    </td>
                    <td>
                        @if($user->IsActive)
                        <span class="badge badge-green">Active</span>
                        @else
                        <span class="badge badge-gray">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="/users/{{ $user->Id }}"
                               class="action-btn view"
                               title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($user->Id !== auth()->user()->Id)
                            <form method="POST"
                                  action="/users/{{ $user->Id }}/toggle"
                                  onsubmit="return confirm('Toggle this user status?')">
                                @csrf
                                <button type="submit"
                                        class="action-btn {{ $user->IsActive ? 'delete' : 'edit' }}"
                                        title="{{ $user->IsActive ? 'Deactivate' : 'Activate' }}">
                                    <i class="bi bi-{{ $user->IsActive ? 'person-slash' : 'person-check' }}"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#aaa;padding:30px">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-bar">
            <div class="page-info">
                Showing {{ $users->firstItem() }}
                to {{ $users->lastItem() }}
                of {{ $users->total() }} users
            </div>
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection