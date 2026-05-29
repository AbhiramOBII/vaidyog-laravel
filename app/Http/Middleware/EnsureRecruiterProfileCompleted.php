<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRecruiterProfileCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->user_type === 'MedicalInstitution' && !$user->is_profile_completed) {
            if (!$request->routeIs('recruiter.onboarding') && !$request->routeIs('recruiter.logout')) {
                return redirect()->route('recruiter.onboarding');
            }
        }

        return $next($request);
    }
}
