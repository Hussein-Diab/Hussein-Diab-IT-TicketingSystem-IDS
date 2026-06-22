@extends('layouts.app')

@section('content')

<div class="topbar">
    <div style="display:flex;align-items:center;gap:10px">
        <a href="/tickets/{{ $ticket->Id }}" style="color:#888;font-size:18px">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="topbar-title">
            Edit — {{ $ticket->RefNumber }}
        </div>
    </div>
    <div class="topbar-right">
        <div class="avatar sm">
            {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
        </div>
    </div>
</div>

<div class="page-content">
    <div class="form-card">

        <div class="form-section-title">Edit Ticket</div>

        @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="/tickets/{{ $ticket->Id }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">
                    Title <span class="form-required">*</span>
                </label>
                <input type="text"
                       name="Title"
                       class="form-control"
                       value="{{ old('Title', $ticket->Title) }}"
                       required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="CategoryId" class="form-control">
                        @foreach($categories as $category)
                        <option value="{{ $category->Id }}"
                            {{ $ticket->CategoryId == $category->Id ? 'selected' : '' }}>
                            {{ $category->Name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="PriorityId" class="form-control">
                        @foreach($priorities as $priority)
                        <option value="{{ $priority->Id }}"
                            {{ $ticket->PriorityId == $priority->Id ? 'selected' : '' }}>
                            {{ $priority->Name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="StatusId" class="form-control">
                    @foreach($statuses as $status)
                    <option value="{{ $status->Id }}"
                        {{ $ticket->StatusId == $status->Id ? 'selected' : '' }}>
                        {{ $status->Name }}
                    </option>
                    @endforeach
                </select>
            </div>
                @if(auth()->user()->isAdminOrManager())
                <div class="form-group">
                    <label class="form-label">Assign To Agent</label>
                    <select name="AssignedTo" class="form-control">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::where('RoleId', 2)->get() as $agent)
                        <option value="{{ $agent->Id }}"
                            {{ $ticket->AssignedTo == $agent->Id ? 'selected' : '' }}>
                            {{ $agent->Name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="Description"
                          class="form-control"
                          rows="5"
                          required>{{ old('Description', $ticket->Description) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="/tickets/{{ $ticket->Id }}" 
                   class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check"></i> Update Ticket
                </button>
            </div>

        </form>
    </div>
</div>

@endsection