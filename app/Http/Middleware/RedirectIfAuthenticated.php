<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
//                return redirect(RouteServiceProvider::HOME);
                $role = Auth::user()->role;
                if($role == '0'){
                    return redirect()->route('gallery.index');
                }else if($role == '1'){
                    //admin
                    return redirect()->route('users.index');
                }
            }
        }

        return $next($request);
    }
}
