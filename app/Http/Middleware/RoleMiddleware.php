<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (in_array('guest', $roles)) {
            if (Auth::check()) {
                // $role = Auth::user()->role;

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'You are already authenticated.',
                        'redirect' => route("dashboard")
                    ], 403);
                }

                return redirect()
                    ->route("dashboard")
                    ->with('toast', [
                        'type' => 'info',
                        'message' => 'Anda sudah login'
                    ]);
            }

            return $next($request);
        }

        if (!Auth::check()) {
            return $this->unauthorized($request, 'Silakan login terlebih dahulu.');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            return $this->unauthorized($request, 'Role Anda tidak memiliki akses.');
        }

        return $next($request);
    }

    protected function unauthorized(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message
            ], 403);
        }

        return redirect()
            ->route('login')
            ->with('toast', [
                'type' => 'error',
                'message' => $message
            ]);
    }
}
