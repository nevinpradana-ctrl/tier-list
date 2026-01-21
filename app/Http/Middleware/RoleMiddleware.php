<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin,staff_editor')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Anda harus login terlebih dahulu');
        }

        if (empty($roles)) {
            return $next($request);
        }

        // roles may be passed as multiple parameters or a single comma-separated string
        $allowed = [];
        foreach ($roles as $r) {
            foreach (array_map('trim', explode(',', $r)) as $rr) {
                if ($rr !== '') {
                    $allowed[] = $rr;
                }
            }
        }
        $user = Auth::user();

        Log::info('RoleMiddleware check', ['user_id' => $user?->id, 'user_role' => $user?->role, 'allowed' => $allowed]);

        if (!$user || !in_array($user->role, $allowed, true)) {
            Log::warning('RoleMiddleware denied', ['user_id' => $user?->id, 'user_role' => $user?->role, 'allowed' => $allowed]);
            
            // Format allowed roles for display
            $rolesText = implode(', ', array_map(function($role) {
                return '"' . $role . '"';
            }, $allowed));
            
            return redirect()->back()->with('warning', "Akses ditolak! Hanya role {$rolesText} yang bisa mengakses halaman ini.");
        }

        return $next($request);
    }
}

