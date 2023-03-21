<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (!config('app.demo')){

            return $next($request);

        }elseif($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE') || $request->route()->getName() == 'admin.user.login'){

            notify()->warning('You cannot change anything in this demo version', 'warning');
            return redirect()->back();
        }
        return $next($request);
    }
}
