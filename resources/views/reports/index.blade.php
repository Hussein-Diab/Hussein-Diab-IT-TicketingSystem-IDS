@extends('layouts.app')

@section('content')
    <div class="topbar">
        <div class="topbar-title">Reports</div>
        <div class="topbar-right">
            {{-- Export button --}}
            <button onclick="exportToPDF()" class="btn-primary">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
            <div class="avatar sm">
                {{ strtoupper(substr(auth()->user()->Name, 0, 2)) }}
            </div>
        </div>
    </div>
    <div class="page-content" id="reportContent">
        <div class="report-header">
            <div>
                <div style="font-size:20px;font-weight:700;color:#1a1a2e">
                    HelpDesk IDS — System Report
                </div>
                <div style="font-size:13px;color:#888;margin-top:4px">
                    Generated on {{ now()->format('d M Y, h:i A') }}
                    by {{ auth()->user()->Name }}
                </div>
            </div>
        </div>
        <div class="form-section-title" style="margin-bottom:12px">
            Ticket Overview
        </div>
        <div class="stats-grid" style="margin-bottom:20px">
            <div class="stat-card">
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $totalTickets }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Open</div>
                <div class="stat-value" style="color:#6C63FF">
                    {{ $openTickets }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">In Progress</div>
                <div class="stat-value" style="color:#e67e22">
                    {{ $inProgressTickets }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending</div>
                <div class="stat-value" style="color:#e91e63">
                    {{ $pendingTickets }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Resolved</div>
                <div class="stat-value" style="color:#2e7d32">
                    {{ $resolvedTickets }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Closed</div>
                <div class="stat-value" style="color:#888">
                    {{ $closedTickets }}
                </div>
            </div>
        </div>
        <div class="bottom-grid" style="margin-bottom:20px">
            <div class="card">
                <div class="card-title" style="margin-bottom:14px">
                    Tickets by Category
                </div>
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:#fafafa">
                            <th
                                style="padding:8px 12px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                                Category
                            </th>
                            <th
                                style="padding:8px 12px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                                Total
                            </th>
                            <th
                                style="padding:8px 12px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                                Share
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketsByCategory as $cat)
                            <tr>
                                <td style="padding:8px 12px;font-size:13px">
                                    {{ $cat->Name }}
                                </td>
                                <td style="padding:8px 12px;font-size:13px;text-align:center;font-weight:600;color:#6C63FF">
                                    {{ $cat->total }}
                                </td>
                                <td style="padding:8px 12px">
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <div style="flex:1;height:6px;background:#f0f0f4;border-radius:3px">
                                            <div
                                                style="width:{{ $totalTickets > 0 ? ($cat->total / $totalTickets) * 100 : 0 }}%;height:100%;background:#6C63FF;border-radius:3px">
                                            </div>
                                        </div>
                                        <span style="font-size:11px;color:#aaa">
                                            {{ $totalTickets > 0 ? round(($cat->total / $totalTickets) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card">
                <div class="card-title" style="margin-bottom:14px">
                    Tickets by Priority
                </div>
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:#fafafa">
                            <th
                                style="padding:8px 12px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                                Priority
                            </th>
                            <th
                                style="padding:8px 12px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                                Total
                            </th>
                            <th
                                style="padding:8px 12px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                                Share
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticketsByPriority as $priority)
                            @php
                                $pc = [
                                    'Low' => '#2e7d32',
                                    'Medium' => '#6C63FF',
                                    'High' => '#e67e22',
                                    'Critical' => '#c62828',
                                ];
                                $color = $pc[$priority->Name] ?? '#aaa';
                            @endphp
                            <tr>
                                <td style="padding:8px 12px;font-size:13px">
                                    <span
                                        style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $color }};margin-right:6px"></span>
                                    {{ $priority->Name }}
                                </td>
                                <td
                                    style="padding:8px 12px;font-size:13px;text-align:center;font-weight:600;color:{{ $color }}">
                                    {{ $priority->total }}
                                </td>
                                <td style="padding:8px 12px">
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <div style="flex:1;height:6px;background:#f0f0f4;border-radius:3px">
                                            <div
                                                style="width:{{ $totalTickets > 0 ? ($priority->total / $totalTickets) * 100 : 0 }}%;height:100%;background:{{ $color }};border-radius:3px">
                                            </div>
                                        </div>
                                        <span style="font-size:11px;color:#aaa">
                                            {{ $totalTickets > 0 ? round(($priority->total / $totalTickets) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="card" style="margin-bottom:20px">
            <div class="card-title" style="margin-bottom:14px">
                Agent Performance
            </div>
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#fafafa">
                        <th
                            style="padding:10px 14px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Agent</th>
                        <th
                            style="padding:10px 14px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Assigned</th>
                        <th
                            style="padding:10px 14px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            In Progress</th>
                        <th
                            style="padding:10px 14px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Resolved</th>
                        <th
                            style="padding:10px 14px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Resolution Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agentPerformance as $agent)
                        <tr>
                            <td style="padding:10px 14px;font-size:13px">
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="comment-avatar" style="width:28px;height:28px;font-size:10px">
                                        {{ strtoupper(substr($agent->Name, 0, 2)) }}
                                    </div>
                                    {{ $agent->Name }}
                                </div>
                            </td>
                            <td style="padding:10px 14px;text-align:center;font-size:13px;font-weight:600">
                                {{ $agent->totalAssigned }}
                            </td>
                            <td style="padding:10px 14px;text-align:center">
                                <span class="badge badge-orange">{{ $agent->inProgress }}</span>
                            </td>
                            <td style="padding:10px 14px;text-align:center">
                                <span class="badge badge-green">{{ $agent->resolved }}</span>
                            </td>
                            <td style="padding:10px 14px;text-align:center;font-size:13px;font-weight:600;color:#6C63FF">
                                {{ $agent->totalAssigned > 0 ? round(($agent->resolved / $agent->totalAssigned) * 100) : 0 }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#aaa;padding:20px">
                                No agents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card" style="margin-bottom:20px">
            <div class="card-title" style="margin-bottom:14px">
                Most Active Employees (by tickets submitted)
            </div>
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#fafafa">
                        <th
                            style="padding:10px 14px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            #</th>
                        <th
                            style="padding:10px 14px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Employee</th>
                        <th
                            style="padding:10px 14px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Tickets Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeEmployees as $index => $employee)
                        <tr>
                            <td style="padding:10px 14px;font-size:13px;color:#aaa">
                                {{ $index + 1 }}
                            </td>
                            <td style="padding:10px 14px;font-size:13px">
                                {{ $employee->Name }}
                            </td>
                            <td style="padding:10px 14px;text-align:center;font-size:13px;font-weight:600;color:#6C63FF">
                                {{ $employee->total }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:#aaa;padding:20px">
                                No data yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <div class="card-title" style="margin-bottom:14px">
                Monthly Ticket Trend (Last 6 Months)
            </div>
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#fafafa">
                        <th
                            style="padding:10px 14px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Month</th>
                        <th
                            style="padding:10px 14px;text-align:center;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Tickets</th>
                        <th
                            style="padding:10px 14px;text-align:left;font-size:12px;color:#888;border-bottom:1px solid #e0e0e8">
                            Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ticketsPerMonth as $month)
                        <tr>
                            <td style="padding:10px 14px;font-size:13px">
                                {{ $month->month }}
                            </td>
                            <td style="padding:10px 14px;text-align:center;font-size:13px;font-weight:600;color:#6C63FF">
                                {{ $month->total }}
                            </td>
                            <td style="padding:10px 14px">
                                <div style="height:8px;background:#f0f0f4;border-radius:4px">
                                    @php
                                        $maxMonth = $ticketsPerMonth->max('total');
                                    @endphp
                                    <div
                                        style="width:{{ $maxMonth > 0 ? ($month->total / $maxMonth) * 100 : 0 }}%;height:100%;background:#6C63FF;border-radius:4px">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function exportToPDF() {
            const element = document.getElementById('reportContent');
            const options = {
                margin: 0.5,
                filename: 'HelpDesk-Report-{{ now()->format('Y-m-d') }}.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(options).from(element).save();
        }
    </script>
@endsection
