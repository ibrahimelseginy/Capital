<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role !== $role) {
            // Redirect based on their actual role
            $userRole = Auth::user()->role;
            if ($userRole === 'admin') {
                return redirect('/admin');
            } elseif ($userRole === 'entrepreneur') {
                return redirect('/entrepreneur');
            } else {
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
