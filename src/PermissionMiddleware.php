<?php

namespace Bunthoeuntok\SimplePermission;

use Bunthoeuntok\SimplePermission\Facades\SimplePermission;
use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        dd($request);
        $routeName = $request->route()->getName();
        if ($request->method() === 'POST' || $request->method() === 'PUT' || $request->method() === 'PATCH') {
            return $next($request);
        }

        if (!request()->user()->isAdmin() && !SimplePermission::checkWhiteList($routeName)) {
            return redirect()->back()->with('message', 'You don\'t have permission to acces this page');
        }
    }
}
