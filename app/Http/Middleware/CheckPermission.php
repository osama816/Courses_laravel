<?php

namespace App\Http\Middleware;

use App\Helper\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! $request->user()->can($permission)) {
            if ($request->is('api/*')) {
                return ApiResponse::error('You do not have permission to access this resource.', $permission, 403);
            }else{
                return abort(403,'You do not have permission to access this resource.');
            }
        }

        return $next($request);
    }
}
