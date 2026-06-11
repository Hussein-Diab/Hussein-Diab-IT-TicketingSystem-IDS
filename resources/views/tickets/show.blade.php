@extends('layouts.app')

@section('content')

<div class="topbar">
    <div style="display:flex;align-items:center;gap:10px">
        <a href="/tickets" style="color:#888;font-size:18px">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="topbar-title">
            {{ $ticket->RefNumber }} — {{ $ticket->Title }}
        </div>
    </div>
    <div class="topbar-right">
        <a href="/tickets/{{ $ticket->Id }}/edit" 
           class="btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="page-content">
    <div class="show-grid">

        <div class="form-card">
            <div class="form-section-title">Ticket Details</div>

            <div class="detail-row">
                <div class="detail-label">Reference</div>
                <div class="detail-value ticket-id-col">
                    {{ $ticket->RefNumber }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Title</div>
                <div class="detail-value">{{ $ticket->Title }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Description</div>
                <div class="detail-value">{{ $ticket->Description }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Category</div>
                <div class="detail-value">
                    {{ $ticket->category->Name ?? '-' }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Priority</div>
                <div class="detail-value">
                    @php
                        $pc = ['Low'=>'badge-green','Medium'=>'badge-purple','High'=>'badge-orange','Critical'=>'badge-red'];
                    @endphp
                    <span class="badge {{ $pc[$ticket->priority->Name ?? ''] ?? 'badge-gray' }}">
                        {{ $ticket->priority->Name ?? '-' }}
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @php
                        $sc = ['Open'=>'badge-purple','In Progress'=>'badge-orange','Pending'=>'badge-pink','Resolved'=>'badge-green','Closed'=>'badge-gray'];
                    @endphp
                    <span class="badge {{ $sc[$ticket->status->Name ?? ''] ?? 'badge-gray' }}">
                        {{ $ticket->status->Name ?? '-' }}
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Created by</div>
                <div class="detail-value">
                    {{ $ticket->user->Name ?? '-' }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Created at</div>
                <div class="detail-value">
                    {{ $ticket->created_at->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>

        <div>
            <div class="form-card">
                <div class="form-section-title">Actions</div>
                <a href="/tickets/{{ $ticket->Id }}/edit" 
                   class="btn-primary" 
                   style="width:100%;justify-content:center;margin-bottom:10px">
                    <i class="bi bi-pencil"></i> Edit Ticket
                </a>
                <form method="POST" 
                      action="/tickets/{{ $ticket->Id }}"
                      onsubmit="return confirm('Delete this ticket?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn-danger" 
                            style="width:100%">
                        <i class="bi bi-trash"></i> Delete Ticket
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection