<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 * ─────────────────────────────────────────────────────────────────────────────
 * ZERO Eloquent. Role name fetched via DB::table() join.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('customer.login');
        }

        // Get role name using Query Builder — NOT Auth::user()->role->name (Eloquent)
        $roleName = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->where('users.id', Auth::id())
            ->value('roles.name');

        if (! in_array($roleName, $roles)) {
            if (in_array($roleName, ['admin', 'staff'])) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You do not have permission to access that page.');
            }
            return redirect()->route('customer.dashboard')
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}