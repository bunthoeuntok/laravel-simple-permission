<?php

namespace Bunthoeuntok\SimplePermission\Middlewares;

use Bunthoeuntok\SimplePermission\Exceptions\UnauthorizedException;
use Bunthoeuntok\SimplePermission\Facades\SimplePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();
        if ($request->method() === 'POST' || $request->method() === 'PUT' || $request->method() === 'PATCH') {
            return $next($request);
        }

        if (! request()->user()->isAdmin() && ! SimplePermission::checkWhiteList($routeName)) {
            throw new UnauthorizedException(403);
        }

        View::share('permision', [
            'all' => SimplePermission::allPermissions(),
        ]);

        return $next($request);
    }
}
