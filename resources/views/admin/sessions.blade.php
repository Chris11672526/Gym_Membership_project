@extends('layouts.admin')

@section('page_title', 'Active Sessions')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">Active Sessions</h1>
            <p class="text-muted">Monitor active user sessions</p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Current Sessions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Session ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sessions as $session)
                            <tr>
                                <td><code style="font-size: 0.8rem;">{{ substr($session->id, 0, 12) }}...</code></td>
                                <td><strong>{{ $session->name }}</strong></td>
                                <td>{{ $session->email }}</td>
                                <td><small>{{ $session->ip_address ?? 'N/A' }}</small></td>
                                <td><small>{{ substr($session->user_agent, 0, 30) }}...</small></td>
                                <td>
                                    @php
                                        $lastActivity = date('M d, Y H:i:s', $session->last_activity);
                                    @endphp
                                    {{ $lastActivity }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    No active sessions
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection