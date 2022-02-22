<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits\funcsTrait;
use Auth;

class CheckUserStatus
{
    use funcsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->user()) or $request->user()->status != "A") {
            Auth::logout();
            $this->setFlashMessage('Your account is inactive !', 'danger', 'Frontend');
            return redirect('/login');
        }

        return $next($request);
    }
}
