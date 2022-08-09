<?php

namespace App\Http\Middleware;

use App\Traits\SendResponse;
use Closure;
use Illuminate\Http\Request;

class ProcessingAndAccountingManageMiddleware
{
    use SendResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->user_type == 1) {
            return $next($request);
        } else {
            return $this->send_response(402, 'غير مصرح لك بالدخول', [], []);
        }
    }
}