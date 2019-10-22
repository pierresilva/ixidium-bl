<?php

namespace pierresilva\Sentinel\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserHasRole
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new UserHasPermission instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Run the request filter.
     *
     * @param \Request  $request
     * @param Closure   $next
     * @param string    $role
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response|void
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $this->auth->check()) {
            return response()->json([
                'message' => 'Unauthorized.',
                'errors' => [
                    'unauthenticate' => 'No esta autenticado en el sistema',
                ]
            ], 401);
        }

        if (! $this->auth->user()->isRole($role)) {
            if (\Request::is('api/*')) {
                return response()->json([
                    'message' => 'Unauthorized.',
                    'errors' => [
                        'unathorized' => 'No cuenta con el rol necesario!'
                    ]
                ], 401);
            }

            return abort(401);
        }

        return $next($request);
    }
}
