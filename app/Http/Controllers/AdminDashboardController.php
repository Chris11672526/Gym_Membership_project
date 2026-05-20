<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    private function stats(): array
    {
        return [
            'totalCustomers' => DB::table('customers')->count(),
            'activeMemberships' => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships' => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments' => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue' => DB::table('payments')->where('status', 'Paid')->sum('total_paid'),
            'sessionCount' => DB::table('attendance')->count(),
        ];
    }

    private function monthlyRegistrations()
    {
        return DB::table('customers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function index()
    {
        $monthlyRegistrations = $this->monthlyRegistrations();

        return view('admin.dashboard', [
            ...$this->stats(),
            'customers' => DB::table('customers')->latest()->limit(6)->get(),
            'payments' => DB::table('payments')->latest('payment_date')->limit(5)->get(),
            'monthlyLabels' => $monthlyRegistrations->pluck('month'),
            'monthlyValues' => $monthlyRegistrations->pluck('total'),
        ]);
    }

    public function members()
    {
        return view('admin.members', [
            ...$this->stats(),
            'customers' => DB::table('customers')
                ->leftJoin('memberships', 'memberships.customer_id', '=', 'customers.id')
                ->leftJoin('membership_plans', 'membership_plans.id', '=', 'memberships.membership_plan_id')
                ->select('customers.*', 'memberships.status as membership_status', 'memberships.expiration_date', 'membership_plans.name as plan_name')
                ->orderByDesc('customers.created_at')
                ->get(),
        ]);
    }

    public function payments()
    {
        return view('admin.payments', [
            ...$this->stats(),
            'payments' => DB::table('payments')
                ->join('customers', 'customers.id', '=', 'payments.customer_id')
                ->select('payments.*', 'customers.first_name', 'customers.last_name', 'customers.email')
                ->latest('payments.payment_date')
                ->get(),
        ]);
    }

    public function sessions()
    {
        return view('admin.sessions', [
            ...$this->stats(),
            'attendance' => DB::table('attendance')
                ->join('customers', 'customers.id', '=', 'attendance.customer_id')
                ->join('branches', 'branches.id', '=', 'attendance.branch_id')
                ->leftJoin('classes', 'classes.id', '=', 'attendance.class_id')
                ->select('attendance.*', 'customers.first_name', 'customers.last_name', 'branches.name as branch_name', 'classes.name as class_name')
                ->latest('attendance.check_in')
                ->get(),
        ]);
    }

    public function equipment()
    {
        return view('admin.equipment', [
            ...$this->stats(),
            'equipment' => DB::table('equipment')
                ->join('branches', 'branches.id', '=', 'equipment.branch_id')
                ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment.category_id')
                ->select('equipment.*', 'branches.name as branch_name', 'equipment_categories.name as category_name')
                ->orderBy('equipment.name')
                ->get(),
        ]);
    }

    public function reports()
    {
        $monthlyRegistrations = $this->monthlyRegistrations();

        return view('admin.reports', [
            ...$this->stats(),
            'monthlyLabels' => $monthlyRegistrations->pluck('month'),
            'monthlyValues' => $monthlyRegistrations->pluck('total'),
            'membershipBreakdown' => DB::table('memberships')
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get(),
        ]);
    }
}
