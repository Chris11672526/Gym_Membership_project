<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * AdminDashboardController
 * ─────────────────────────────────────────────────────────────────────────────
 * ZERO Eloquent ORM. All queries use DB::table() (Query Builder) only.
 * No Model imports, no Model::method() calls anywhere.
 */
class AdminDashboardController extends Controller
{
    // ── Shared stats used across all admin views ──────────────────────────────
    private function stats(): array
    {
        return [
            'totalCustomers'     => DB::table('customers')->whereNull('deleted_at')->count(),
            'activeMemberships'  => DB::table('memberships')->where('status', 'Active')->count(),
            'expiredMemberships' => DB::table('memberships')->where('status', 'Expired')->count(),
            'pendingPayments'    => DB::table('payments')->where('status', 'Pending')->count(),
            'totalRevenue'       => DB::table('payments')->where('status', 'Paid')->sum('total_paid'),
            'sessionCount'       => DB::table('attendance')->count(),
        ];
    }

    private function monthlyRegistrations()
    {
        return DB::table('customers')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    // ── Dashboard home ────────────────────────────────────────────────────────
    public function index()
    {
        $monthlyRegistrations = $this->monthlyRegistrations();

        return view('admin.dashboard', [
            ...$this->stats(),
            'customers'     => DB::table('customers')->whereNull('deleted_at')->latest()->limit(6)->get(),
            'payments'      => DB::table('payments')->latest('payment_date')->limit(5)->get(),
            'monthlyLabels' => $monthlyRegistrations->pluck('month'),
            'monthlyValues' => $monthlyRegistrations->pluck('total'),
        ]);
    }

    // ── Members list ──────────────────────────────────────────────────────────
    public function members()
    {
        $customers = DB::table('customers')
            ->leftJoin('memberships', function ($join) {
                $join->on('memberships.customer_id', '=', 'customers.id')
                     ->whereRaw('memberships.id = (
                         SELECT id FROM memberships m2
                         WHERE m2.customer_id = customers.id
                         ORDER BY m2.id DESC LIMIT 1
                     )');
            })
            ->leftJoin('membership_plans', 'membership_plans.id', '=', 'memberships.membership_plan_id')
            ->whereNull('customers.deleted_at')
            ->select(
                'customers.*',
                'memberships.status as membership_status',
                'memberships.expiration_date',
                'membership_plans.name as plan_name'
            )
            ->orderByDesc('customers.created_at')
            ->get();

        return view('admin.members', [
            ...$this->stats(),
            'customers' => $customers,
        ]);
    }

    // ── Add member form ───────────────────────────────────────────────────────
    public function createMember()
    {
        return view('admin.members-create', [
            ...$this->stats(),
            'branches' => DB::table('branches')->whereNull('deleted_at')->get(),
            'plans'    => DB::table('membership_plans')->where('is_active', 1)->get(),
        ]);
    }

    // ── Store new member (AJAX) ───────────────────────────────────────────────
    public function storeMember(Request $request)
    {
        $validated = $request->validate([
            'first_name'         => ['required', 'string', 'max:60'],
            'last_name'          => ['required', 'string', 'max:60'],
            'email'              => ['required', 'email', 'unique:customers,email'],
            'phone'              => ['required', 'regex:/^09[0-9]{9}$/'],
            'gender'             => ['required', 'in:Male,Female,Other'],
            'birthdate'          => ['required', 'date'],
            'branch_id'          => ['required', 'exists:branches,id'],
            'membership_plan_id' => ['nullable', 'exists:membership_plans,id'],
            'status'             => ['required', 'in:Active,Inactive,Suspended'],
        ]);

        DB::transaction(function () use ($validated) {
            $customerId = DB::table('customers')->insertGetId([
                'branch_id'  => $validated['branch_id'],
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'gender'     => $validated['gender'],
                'birthdate'  => $validated['birthdate'],
                'phone'      => $validated['phone'],
                'email'      => $validated['email'],
                'status'     => $validated['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (! empty($validated['membership_plan_id'])) {
                $plan = DB::table('membership_plans')->where('id', $validated['membership_plan_id'])->first();
                $start = now()->toDateString();

                $membershipId = DB::table('memberships')->insertGetId([
                    'customer_id'        => $customerId,
                    'membership_plan_id' => $plan->id,
                    'start_date'         => $start,
                    'expiration_date'    => now()->addDays($plan->duration_days)->toDateString(),
                    'status'             => 'Active',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                DB::table('payments')->insert([
                    'membership_id'  => $membershipId,
                    'customer_id'    => $customerId,
                    'amount'         => $plan->price,
                    'discount'       => 0,
                    'total_paid'     => $plan->price,
                    'payment_method' => 'Cash',
                    'payment_date'   => now(),
                    'status'         => 'Pending',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Member added successfully.']);
        }
        return redirect()->route('admin.members')->with('success', 'Member added successfully.');
    }

    // ── Edit member form ──────────────────────────────────────────────────────
    public function editMember(int $id)
    {
        $customer = DB::table('customers')->where('id', $id)->whereNull('deleted_at')->first();
        abort_unless($customer, 404);

        return view('admin.members-edit', [
            ...$this->stats(),
            'customer' => $customer,
            'branches' => DB::table('branches')->whereNull('deleted_at')->get(),
        ]);
    }

    // ── Update member (AJAX) ──────────────────────────────────────────────────
    public function updateMember(Request $request, int $id)
    {
        $customer = DB::table('customers')->where('id', $id)->whereNull('deleted_at')->first();
        abort_unless($customer, 404);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:60'],
            'last_name'  => ['required', 'string', 'max:60'],
            'email'      => ['required', 'email'],
            'phone'      => ['required', 'regex:/^09[0-9]{9}$/'],
            'gender'     => ['required', 'in:Male,Female,Other'],
            'birthdate'  => ['required', 'date'],
            'branch_id'  => ['required', 'exists:branches,id'],
            'status'     => ['required', 'in:Active,Inactive,Suspended'],
        ]);

        DB::table('customers')->where('id', $id)->update([
            ...$validated,
            'updated_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Member updated successfully.']);
        }
        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }

    // ── Delete member (AJAX soft delete) ─────────────────────────────────────
    public function deleteMember(Request $request, int $id)
    {
        $customer = DB::table('customers')->where('id', $id)->whereNull('deleted_at')->first();
        abort_unless($customer, 404);

        DB::table('customers')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Member deleted.']);
        }
        return redirect()->route('admin.members')->with('success', 'Member deleted.');
    }

    // ── Payments list ─────────────────────────────────────────────────────────
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

    // ── Update payment status (AJAX) ──────────────────────────────────────────
    public function updatePayment(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Paid,Pending,Refunded'],
        ]);

        $payment = DB::table('payments')->where('id', $id)->first();
        abort_unless($payment, 404);

        DB::table('payments')->where('id', $id)->update([
            'status'     => $validated['status'],
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Payment status updated.']);
    }

    // ── Sessions / attendance ─────────────────────────────────────────────────
    public function sessions()
    {
        return view('admin.sessions', [
            ...$this->stats(),
            'attendance' => DB::table('attendance')
                ->join('customers', 'customers.id', '=', 'attendance.customer_id')
                ->join('branches', 'branches.id', '=', 'attendance.branch_id')
                ->leftJoin('classes', 'classes.id', '=', 'attendance.class_id')
                ->select(
                    'attendance.*',
                    'customers.first_name',
                    'customers.last_name',
                    'branches.name as branch_name',
                    'classes.name as class_name'
                )
                ->latest('attendance.check_in')
                ->get(),
        ]);
    }

    // ── Equipment list ────────────────────────────────────────────────────────
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

    // ── Update equipment (AJAX) ───────────────────────────────────────────────
    public function updateEquipment(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity'  => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:New,Good,Fair,Needs Repair,Retired'],
        ]);

        $item = DB::table('equipment')->where('id', $id)->first();
        abort_unless($item, 404);

        DB::table('equipment')->where('id', $id)->update([
            'quantity'   => $validated['quantity'],
            'condition'  => $validated['condition'],
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Equipment updated.']);
    }

    // ── Reports ───────────────────────────────────────────────────────────────
    public function reports()
    {
        $monthlyRegistrations = $this->monthlyRegistrations();

        $membershipBreakdown = DB::table('memberships')
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.reports', [
            ...$this->stats(),
            'monthlyLabels'       => $monthlyRegistrations->pluck('month'),
            'monthlyValues'       => $monthlyRegistrations->pluck('total'),
            'membershipBreakdown' => $membershipBreakdown,
        ]);
    }
}