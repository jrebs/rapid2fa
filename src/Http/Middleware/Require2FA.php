<?php

namespace Jrebs\Rapid2FA\Http\Middleware;

use Closure;

class Require2FA
{
    /**
     * Handle an incoming request.
     *
     * @todo Make the error response come out of config()
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->google2fa_secret) {
            session()->put('intended.url', $request->getRequestUri());
            return redirect()->to(route('rapid2fa.enable'))->with(
                'error',
                config('rapid2fa.denied_text')
            );
        }
        return $next($request);
    }
}
