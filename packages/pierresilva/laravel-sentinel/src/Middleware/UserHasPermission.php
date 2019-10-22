<?php

namespace pierresilva\Sentinel\Middleware;

use pierresilva\Sentinel\Models\Role;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserHasPermission
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
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param array|string $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        $permissions = explode('|', $permissions);

        if ($this->auth->check()) {
            if (!$this->auth->user()->canAtLeast($permissions)) {
                if (\Request::is('api/*')) {
                    return response()->json([
                        'message' => 'Unauthorized.',
                        'errors' => [
                            'unauthorized' => 'No cuenta con el permiso necesario!',
                        ]
                    ], 401);
                }

                abort(403, 'Unauthorized action.');
            }
        } else {
            $guest = Role::whereSlug('invitado')->first();

            if ($guest) {
                if (!$guest->canAtLeast($permissions)) {
                    if (\Request::is('api/*')) {
                        return response()->json([
                            'message' => 'Unauthorized.',
                            'errors' => [
                                'unauthorized' => 'No cuenta con el permiso necesario!',
                            ]
                        ], 401);
                    }

                    abort(403, 'Unauthorized action.');
                }
            } else {
                if (\Request::is('api/*')) {
                    return response()->json([
                        'message' => 'Unauthorized.',
                        'errors' => [
                            'unauthorized' => 'No cuenta con el permiso necesario!',
                        ]
                    ], 401);
                }

                abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
