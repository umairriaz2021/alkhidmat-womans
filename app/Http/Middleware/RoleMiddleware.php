<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if (!auth()->check()) {
        return redirect()->route('admin.login')->with('error', 'Pehle login karein.');
    }
        if (!auth()->user()->hasRole($role)) {
        auth()->logout(); 
        return redirect()->route('admin.login')->with('error', 'Aapke paas is page ki ijazat nahi hai.');
    }
        return $next($request);
    }
}
