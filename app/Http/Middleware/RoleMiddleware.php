<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = $user->getUserRole();

        if (!in_array($userRole, $roles)) {
            // Special handling for unassigned users - redirect them to participant routes
            if ($userRole === 'unassigned' && in_array('participant', $roles)) {
                return $next($request);
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. You do not have permission to access this resource.'
                ], 403);
            }

            abort(403, 'Unauthorized access. You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
