<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            session()->flash('not_auth', '로그인이 필요합니다.');
            return back();
            // return redirect('quiz');
            // return redirect('login');
            
        }
        
        return $next($request);
    }
}
