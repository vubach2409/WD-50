<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
       if (Auth::check() && in_array(Auth::user()->role, ['admin', 'nhanvien'])) {
        return $next($request);
    }
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang Admin.');
    }
}
