<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserHasAction
{
  /**
   * @var \Illuminate\Contracts\Auth\Guard
   */
  protected $auth;

  public function __construct(Guard $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next, ...$actions)
  {
    if ($request->get('allow_anonymous')) {
      return $next($request);
    }

    if (!auth()->user() && \Request::is('api/*')) {
      return response()->json([
        'message' => 'No esta autorizado.',
        'errors' => [
          'unauthorized' => 'No cuenta con los permisos necesarios para ejecutar esta acción!',
        ]
      ], 401);
    } else if(!auth()->user()) {
      abort(401, 'Unauthorized action.');
    }

    $user = auth()->user()->toArray();

    foreach ($actions as $action) {
      if (preg_match("~\b{$action}\b~", $user['actions'])) {
        return $next($request);
      }
    }

    if (\Request::is('api/*')) {
      return response()->json([
        'message' => 'No esta autorizado.',
        'errors' => [
          'unauthorized' => 'No cuenta con los permisos necesarios para ejecutar esta acción!',
        ]
      ], 401);
    } else {
      abort(401, 'Unauthorized action.');
    }
  }
}
