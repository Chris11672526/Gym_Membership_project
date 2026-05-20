<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function showRegister()
    {
        return view('customer.auth.register', [
            'plans' => MembershipPlan::where('is_active', 1)->get(),
            'branches' => DB::table('branches')->whereNull('deleted_at')->get(),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'payment_method' => ['required', 'in:Cash,GCash,Credit Card,Bank Transfer,PayMaya'],
            'reference_no' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($validated) {
            $memberRole = Role::where('name', 'member')->firstOrFail();
            $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);
            $nameParts = preg_split('/\s+/', trim($validated['full_name']), 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? 'Member';

            $user = User::create([
                'role_id' => $memberRole->id,
                'branch_id' => $validated['branch_id'],
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'branch_id' => $validated['branch_id'],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => 'Other',
                'birthdate' => '2000-01-01',
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'status' => 'Active',
            ]);

            $start = Carbon::today();
            $membership = Membership::create([
                'customer_id' => $customer->id,
                'membership_plan_id' => $plan->id,
                'start_date' => $start,
                'expiration_date' => $start->copy()->addDays($plan->duration_days),
                'status' => 'Active',
            ]);

            DB::table('payments')->insert([
                'membership_id' => $membership->id,
                'customer_id' => $customer->id,
                'amount' => $plan->price,
                'discount' => 0,
                'total_paid' => $plan->price,
                'payment_method' => $validated['payment_method'],
                'payment_date' => now(),
                'reference_no' => $validated['reference_no'] ?? null,
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Auth::login($user);
        });

        return redirect()->route('customer.dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid customer login details.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()->role?->name !== 'member') {
            Auth::logout();
            return back()->withErrors(['email' => 'This login is for customers only.']);
        }

        return redirect()->route('customer.dashboard');
    }
}
