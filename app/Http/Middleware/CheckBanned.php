<?php
// =============================================
// app/Http/Middleware/CheckBanned.php
// =============================================
// php artisan make:middleware CheckBanned

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->banned_until && $user->banned_until > now()) {
            // Allow only the banned page and logout
            if (!$request->routeIs('banned') && !$request->routeIs('logout')) {
                return redirect()->route('banned');
            }
        }

        return $next($request);
    }
}


// =============================================
// app/Http/Middleware/AdminMiddleware.php
// =============================================
// php artisan make:middleware AdminMiddleware

// namespace App\Http\Middleware;
// use Closure;
// use Illuminate\Http\Request;
//
// class AdminMiddleware
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if (!auth()->check() || !auth()->user()->is_admin) {
//             abort(403);
//         }
//         return $next($request);
//     }
// }


// =============================================
// Register in bootstrap/app.php (Laravel 11)
// OR in app/Http/Kernel.php (Laravel 10)
// =============================================

// Laravel 11 — bootstrap/app.php:
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'banned' => \App\Http\Middleware\CheckBanned::class,
//         'admin'  => \App\Http\Middleware\AdminMiddleware::class,
//     ]);
//     $middleware->appendToGroup('web', \App\Http\Middleware\CheckBanned::class);
// })

// Laravel 10 — Kernel.php $middlewareAliases:
// 'banned' => \App\Http\Middleware\CheckBanned::class,
// 'admin'  => \App\Http\Middleware\AdminMiddleware::class,
// And add CheckBanned to $middlewareGroups['web']
