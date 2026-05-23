<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function showRegister()
    {
        return view('customer.auth.register', [
            'branches' => DB::table('branches')->whereNull('deleted_at')->get(),
            'plans'    => DB::table('membership_plans')->where('is_active', 1)->get(),
        ]);
    }

    // ── Login — returns JSON for AJAX ─────────────────────────────────────
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt(
            ['email' => $validated['email'], 'password' => $validated['password']],
            $request->boolean('remember')
        )) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid email or password.'], 422);
            }
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Check role using Query Builder — NO Eloquent
        $roleName = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->where('users.id', Auth::id())
            ->value('roles.name');

        if ($roleName !== 'member') {
            Auth::logout();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This login is for customers only.'], 403);
            }
            return back()->withErrors(['email' => 'This login is for customers only.']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Login successful.',
                'redirect' => route('customer.dashboard'),
            ]);
        }

        return redirect()->route('customer.dashboard');
    }

    // ── Register ──────────────────────────────────────────────────────────
    public function register(Request $request)
    {
        $validated = $request->validate([
            'branch_id'          => ['required', 'exists:branches,id'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'full_name'          => ['required', 'string', 'max:120'],
            'phone'              => ['required', 'regex:/^09[0-9]{9}$/'],
            'email'              => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'           => ['required', 'confirmed', Password::min(8)],
            'payment_method'     => ['nullable', 'in:Cash,GCash,Credit Card,Bank Transfer,PayMaya'],
            'reference_no'       => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($validated) {
            // Get member role
            $memberRole = DB::table('roles')->where('name', 'member')->first();
            abort_unless($memberRole, 500, 'Member role not found. Run php artisan db:seed.');

            // Get membership plan
            $plan = DB::table('membership_plans')
                ->where('id', $validated['membership_plan_id'])
                ->first();
            abort_unless($plan, 422, 'Invalid membership plan.');

            // Split full name into first/last
            $nameParts = preg_split('/\s+/', trim($validated['full_name']), 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? 'Member';

            // 1. Insert into users table
            $userId = DB::table('users')->insertGetId([
                'role_id'    => $memberRole->id,
                'branch_id'  => $validated['branch_id'],
                'name'       => $validated['full_name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Insert into customers table (correct column names)
            $customerId = DB::table('customers')->insertGetId([
                'user_id'    => $userId,
                'branch_id'  => $validated['branch_id'],
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'gender'     => 'Other',
                'birthdate'  => '2000-01-01',
                'phone'      => $validated['phone'],
                'email'      => $validated['email'],
                'status'     => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Insert into memberships table (correct column names)
            $start        = Carbon::today();
            $membershipId = DB::table('memberships')->insertGetId([
                'customer_id'        => $customerId,
                'membership_plan_id' => $plan->id,           // ✅ correct column
                'start_date'         => $start->toDateString(),
                'expiration_date'    => $start->copy()->addDays($plan->duration_days)->toDateString(), // ✅ correct column
                'status'             => 'Active',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // 4. Insert payment record
            DB::table('payments')->insert([
                'membership_id'  => $membershipId,
                'customer_id'    => $customerId,
                'amount'         => $plan->price,
                'discount'       => 0,
                'total_paid'     => $plan->price,
                'payment_method' => $validated['payment_method'] ?? 'Cash',
                'payment_date'   => now(),
                'reference_no'   => $validated['reference_no'] ?? null,
                'status'         => 'Pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 5. Log in the new user
            Auth::loginUsingId($userId);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Registration successful!',
                'redirect' => route('customer.dashboard'),
            ]);
        }

        return redirect()->route('customer.dashboard');
    }
}
