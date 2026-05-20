<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerDashboardController extends Controller
{
    private function customerData(): array
    {
        $customer = Auth::user()->customer;

        $membership = DB::table('memberships')
            ->join('membership_plans', 'membership_plans.id', '=', 'memberships.membership_plan_id')
            ->where('memberships.customer_id', $customer->id)
            ->orderByDesc('memberships.id')
            ->select('memberships.*', 'membership_plans.name as plan_name', 'membership_plans.price', 'membership_plans.features')
            ->first();

        $daysRemaining = $membership
            ? max(0, Carbon::today()->diffInDays(Carbon::parse($membership->expiration_date), false))
            : 0;

        return [
            'customer' => $customer,
            'membership' => $membership,
            'daysRemaining' => $daysRemaining,
            'branch' => DB::table('branches')->where('id', $customer->branch_id)->first(),
            'equipment' => DB::table('equipment')->where('branch_id', $customer->branch_id)->get(),
            'trainers' => DB::table('trainers')->where('branch_id', $customer->branch_id)->where('status', 'Active')->get(),
            'classes' => DB::table('classes')->where('branch_id', $customer->branch_id)->where('is_active', 1)->get(),
            'payments' => DB::table('payments')->where('customer_id', $customer->id)->latest('payment_date')->get(),
        ];
    }

    public function index()
    {
        return view('customer.dashboard', $this->customerData());
    }

    public function payments()
    {
        return view('customer.payments', $this->customerData());
    }

    public function cancelMembership()
    {
        $customer = Auth::user()->customer;

        $membership = DB::table('memberships')
            ->where('customer_id', $customer->id)
            ->where('status', 'Active')
            ->latest('id')
            ->first();

        if (! $membership) {
            return back()->with('membership_status', 'No active membership found to cancel.');
        }

        DB::table('memberships')
            ->where('id', $membership->id)
            ->where('customer_id', $customer->id)
            ->update([
                'status' => 'Cancelled',
                'notes' => trim(($membership->notes ? $membership->notes."\n" : '').'Cancelled by customer on '.now()->format('Y-m-d H:i:s')),
                'updated_at' => now(),
            ]);

        return redirect()->route('customer.dashboard')->with('membership_status', 'Your membership has been cancelled.');
    }

    public function payPending(Request $request, int $payment)
    {
        $customer = Auth::user()->customer;

        $validated = $request->validate([
            'payment_method' => ['required', 'in:Cash,GCash,Credit Card,Bank Transfer,PayMaya'],
            'reference_no' => ['nullable', 'string', 'max:100'],
        ]);

        $existingPayment = DB::table('payments')
            ->where('id', $payment)
            ->where('customer_id', $customer->id)
            ->first();

        abort_unless($existingPayment, 404);

        if ($existingPayment->status !== 'Pending') {
            return back()->with('payment_status', 'This payment is already processed.');
        }

        DB::table('payments')
            ->where('id', $payment)
            ->where('customer_id', $customer->id)
            ->update([
                'payment_method' => $validated['payment_method'],
                'reference_no' => $validated['reference_no'] ?? $existingPayment->reference_no,
                'payment_date' => now(),
                'status' => 'Paid',
                'updated_at' => now(),
            ]);

        return redirect()->route('customer.payments')->with('payment_status', 'Payment submitted successfully.');
    }

    public function classes()
    {
        return view('customer.classes', $this->customerData());
    }

    public function equipment()
    {
        return view('customer.equipment', $this->customerData());
    }

    public function trainers()
    {
        return view('customer.trainers', $this->customerData());
    }
}
