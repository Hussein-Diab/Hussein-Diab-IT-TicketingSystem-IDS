@extends('layouts.app')

@section('content')

<div class="topbar">
    <div style="display:flex;align-items:center;gap:10px">
        <a href="/tickets" style="color:#888;font-size:18px">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="topbar-title">New Ticket</div>
    </div>
    <div class="topbar-right">
        <div class="avatar sm">
            {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
        </div>
    </div>
</div>

<div class="page-content">
    <div class="form-card">

        <div class="form-section-title">Ticket Information</div>

        {{-- hone errors --}}
        @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="/tickets">
            @csrf

            <div class="form-group">
                <label class="form-label">
                    Title <span class="form-required">*</span>
                </label>
                <input type="text"
                       name="Title"
                       class="form-control"
                       placeholder="Brief description of the issue..."
                       value="{{ old('Title') }}"
                       required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        Category <span class="form-required">*</span>
                    </label>
                    <select name="CategoryId" class="form-control" required>
                        <option value="">Select category...</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->Id }}"
                            {{ old('CategoryId') == $category->Id ? 'selected' : '' }}>
                            {{ $category->Name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Priority <span class="form-required">*</span>
                    </label>
                    <select name="PriorityId" class="form-control" required>
                        <option value="">Select priority...</option>
                        @foreach($priorities as $priority)
                        <option value="{{ $priority->Id }}"
                            {{ old('PriorityId') == $priority->Id ? 'selected' : '' }}>
                            {{ $priority->Name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
    @if(auth()->user()->isAdminOrManager())
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Assign To Employee</label>
            <select name="UserId" class="form-control">
                <option value="">Select employee...</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->Id }}">
                    {{ $employee->Name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Assign To Agent</label>
            <select name="AssignedTo" class="form-control">
                <option value="">Select agent...</option>
                @foreach(\App\Models\User::where('RoleId', 2)->get() as $agent)
                <option value="{{ $agent->Id }}">
                    {{ $agent->Name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
            <div class="form-group">
                <label class="form-label">
                    Description <span class="form-required">*</span>
                </label>
                <textarea name="Description"
                          class="form-control"
                          rows="5"
                          placeholder="Describe the issue in detail..."
                          required>{{ old('Description') }}</textarea>
            </div>

           
            <div class="form-actions">
                <a href="/tickets" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-send"></i> Submit Ticket
                </button>
            </div>

        </form>
    </div>
</div>

@endsection