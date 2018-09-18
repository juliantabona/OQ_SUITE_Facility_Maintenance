<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            //  Check if the user already has a company
            $UserStatus = Auth::user()->status;

            //  If the user needs to activate account
            if ($UserStatus == 0) {
                //  Notify the user to activate account
                Session::forget('alert');
                $request->session()->flash('alert', array('Hi '.Auth::user()->first_name.', you need to activate your account first. Check your "'.Auth::user()->email.'" email. Also check your spam folder.', 'icon-exclamation icons', 'warning'));

                //  Redirect to activate account page
                return redirect()->route('activate-show');
            }
        }

        return $next($request);
    }
}
