<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // Super admins bypass all permission checks
        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        // Check if sub-admin has any of the required permissions
        if (!empty($permissions) && !$admin->hasAnyPermission($permissions)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
