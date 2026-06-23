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
            <a href="/tickets/{{ $ticket->Id }}/edit" class="btn-primary">
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
                            $pc = [
                                'Low' => 'badge-green',
                                'Medium' => 'badge-purple',
                                'High' => 'badge-orange',
                                'Critical' => 'badge-red',
                            ];
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
                            $sc = [
                                'Open' => 'badge-purple',
                                'In Progress' => 'badge-orange',
                                'Pending' => 'badge-pink',
                                'Resolved' => 'badge-green',
                                'Closed' => 'badge-gray',
                            ];
                        @endphp
                        <span class="badge {{ $sc[$ticket->status->Name ?? ''] ?? 'badge-gray' }}">
                            {{ $ticket->status->Name ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Requested by</div>
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

                @if ($ticket->attachments->count() > 0)
                    <div class="detail-row">
                        <div class="detail-label">Attachments</div>
                        <div class="detail-value">
                            @foreach ($ticket->attachments as $attachment)
                                <div class="attachment-item">
                                    <i class="bi bi-paperclip" style="color:#6C63FF"></i>
                                    <a href="{{ asset('storage/' . $attachment->FilePath) }}" target="_blank"
                                        class="attachment-link">
                                        {{ $attachment->FileName }}
                                    </a>
                                    <span class="attachment-size">
                                        ({{ number_format($attachment->FileSize / 1024, 1) }} KB)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <div class="form-card">
                    <div class="form-section-title">Actions</div>
                    <a href="/tickets/{{ $ticket->Id }}/edit" class="btn-primary"
                        style="width:100%;justify-content:center;margin-bottom:10px;display:flex">
                        <i class="bi bi-pencil"></i> Edit Ticket
                    </a>
                    <form method="POST" action="/tickets/{{ $ticket->Id }}"
                        onsubmit="return confirm('Delete this ticket?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="width:100%">
                            <i class="bi bi-trash"></i> Delete Ticket
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <div class="form-card" style="margin-top:16px">
            <div class="form-section-title">
                Comments ({{ $ticket->comments->count() }})
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @forelse($ticket->comments as $comment)
                <div class="comment-box">
                    <div class="comment-header">
                        <div class="comment-avatar">
                            {{ strtoupper(substr($comment->user->Name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="comment-name">
                                {{ $comment->user->Name }}
                            </div>
                            <div class="comment-time">
                                {{ $comment->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <div class="comment-body">
                        {{ $comment->Body }}
                    </div>
                </div>
            @empty
                <p class="empty-msg">No comments yet. Be the first to comment!</p>
            @endforelse
            <div
                style="margin-top:16px;
                    padding-top:16px;
                    border-top:1px solid #f0f0f4">
                <div class="form-section-title">Add Comment</div>
                <form method="POST" action="/tickets/{{ $ticket->Id }}/comments">
                    @csrf
                    <div class="form-group">
                        <textarea name="Body" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
                    </div>
                    <div style="display:flex;justify-content:flex-end">
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-send"></i> Post Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>


@endsection
