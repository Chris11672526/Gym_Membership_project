@extends('layouts.admin')

@section('page_title', 'Reports & Analytics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">Reports & Analytics</h1>
            <p class="text-muted">System performance and member statistics</p>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Total Revenue (All Paid Transactions)</h5>
                </div>
                <div class="card-body">
                    <h2 style="color: var(--primary); font-weight: bold;">₱{{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Members by Branch -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Members by Branch</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Branch</th>
                                    <th>Member Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($membersByBranch as $branch)
                                    <tr>
                                        <td><strong>{{ $branch->name }}</strong></td>
                                        <td>
                                            <span class="badge bg-primary">{{ $branch->count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Method</th>
                                    <th>Count</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topPaymentMethods as $method)
                                    <tr>
                                        <td><strong>{{ $method->payment_method }}</strong></td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $method->count }}</span>
                                        </td>
                                        <td>₱{{ number_format($method->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Active Membership Plans -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Active Membership Plans</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Plan</th>
                                    <th>Active Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($membershipPlans as $plan)
                                    <tr>
                                        <td><strong>{{ $plan->name }}</strong></td>
                                        <td>
                                            <span class="badge bg-success">{{ $plan->count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No active memberships</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Enrollment -->
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header text-white" style="background: #6f42c1;">
                    <h5 class="mb-0">Class Enrollment</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Class</th>
                                    <th>Enrolled Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classEnrollments as $class)
                                    <tr>
                                        <td><strong>{{ $class->name }}</strong></td>
                                        <td>
                                            <span class="badge bg-info">{{ $class->count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No enrollments</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection