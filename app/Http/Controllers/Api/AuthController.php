<?php

namespace App\Http\Controllers\Api;

use App\Notifications\RegisteredUser;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

/**
 * @resource Seguridad - Autenticación
 *
 * Proceso de autentificación
 *
 * Class ActivityLogController
 * @package App\Http\Controllers\Api
 * @author Pierre Silva <pierremichelsilva@gmail.com>
 */
class AuthController extends Controller
{
  use SendsPasswordResetEmails;

  /**
   * @var JWTAuth
   */
  private $jwtAuth;

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct(JWTAuth $jwtAuth)
  {
    $this->jwtAuth = $jwtAuth;

    $this->middleware('auth:api', [
      'except' => [
        'login',
        'register',
        'getAllUsers',
        'recoverPassword',
        'resetPassword',
        'refresh',
        'users'
      ]
    ]);
  }

  /**
   * login()
   *
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(LoginRequest $request)
  {
    $credentials = request(['email', 'password']);
    try {
      $token = auth()->attempt($credentials);
    } catch (\Exception $exception) {
      return response()->json([
        'message' => 'Ocurrio un error!',
        'errors' => $exception,
      ], 400);
    }

    if (!$token) {
      return response()->json([
        'message' => 'Credenciales no validas!',
        'errors' => [
          'auth' => 'Unauthorized',
        ],
      ], 401);
    }

    // $user = auth()->user();

    // ToDo: Cron job to set status from expired_at attribute

    /*if($user->status !== 'active' || $user->expire_at < now()) {
        return response()->json([
            'message' => 'Su cuenta expiró!',
            'errors' => [
                'account_expired' => 'Su cuenta expiró!'
            ],
        ], 401);
    }*/

    return $this->respondWithToken($token);
  }

  /**
   * register()
   *
   * Register new user.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(RegisterRequest $request)
  {

    $credentials = request(['name', 'email', 'password']);
    try {
      $user = User::create([
        'name' => $credentials['name'],
        'email' => $credentials['email'],
        'password' => bcrypt($credentials['password']),
        'remember_token' => str_random(10),
        'status' => 'active',
        'expire_at' => now()->addMonth(config('auth.account_add_moths'))->toDateTimeString(),
      ]);
    } catch (\Exception $exception) {
      return response()->json([
        'message' => 'Ocurrio un error!',
        'errors' => $exception,
      ], 400);
    }

    if ($user && !$token = auth()->attempt([
          'email' => $credentials['email'],
          'password' => $credentials['password']]
      )) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    \Notification::send(User::findOrFail(1), new RegisteredUser($user));

    return $this->respondWithToken($token);
  }

  /**
   *
   * me()
   *
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(auth()->user());
  }

  /**
   * logout()
   *
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth()->logout();

    return response()->json(['message' => 'Salio del sistema con éxito!']);
  }

  /**
   * refresh()
   *
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh(Request $request)
  {

    $valid = auth()->validate($request->only(['email', 'password']));

    // dd($valid);

    // dd(auth()->payload()->toArray());


    // dd(auth()->tokenById(1));

    // dd(auth()->userOrFail());
    $token = $this->jwtAuth->getToken();

    if ($token) {
      // Get some user from somewhere
      $user = User::findOrFail($request->get('id'));
      // Get the token
      $token = auth()->login($user);
    }

    return $this->respondWithToken($token);

    /*
    try {
        return $this->respondWithToken(auth()->refresh());
    } catch (\Exception $exception) {
        return response()->json([
            'message' => 'Error !',
            'errors' => $exception,
        ], 200);
    }
    */

  }

  /**
   * respondWithToken()
   *
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token)
  {
    $user = auth()->user();

    $userMeta = $user->toArray();

    $userMeta['roles'] = $user->getRoles();
    $userMeta['permissions'] = $user->getPermissions();
    $userMeta['token_expire_at'] = now()->addSeconds(auth()->factory()->getTTL() * 60)->toAtomString();

    $userMeta = base64_encode(json_encode($userMeta));
    return response()->json([
      'token' => $token,
      'meta' => $userMeta,
    ]);
  }

    /**
     * getAllUsers()
     * TODO:Definición
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function getAllUsers(Request $request)
  {
    if (!\Auth::user()->hasRole('desarrollador')) {
      return response()->json([
        'message' => 'No Cuenta con el rol necesario'
      ], 401);
    }

    if (!\Auth::user()->hasPermission('nivel-desarrollador')) {
      return response()->json([
        'message' => 'No Cuenta con el permiso necesario'
      ], 401);
    }

    $columns = $request->get('columns');

    $order = $request->get('order');

    $orderBy = $columns[$order[0]['column']]['data'];
    $orderDir = $order[0]['dir'];

    $search = $request->get('search');

    $usersList = User::with('roles.permissions');

    if ($search['value']) {
      $usersList->where('name', 'like', "%{$search['value']}%")
        ->orWhere('email', 'like', "%{$search['value']}%")
        ->orWhere('status', 'like', "%{$search['value']}%");
    }

    foreach ($columns as $column) {
      if ($column['search']['value']) {
        $usersList->orWhere($column['data'], 'like', "%{$column['search']['value']}%");
      }
    }

    $users = $usersList
      ->orderBy($orderBy, $orderDir)
      ->offset($request->get('start'))
      ->limit($request->get('length'))
      ->get()->toArray();

    //$users = $usersList['data'];
    //unset($usersList['data']);
    return response()->json([
      'message' => 'Colección obtenida con éxito!',
      'data' => $users,
      //'meta' => $usersList,
      'draw' => $request->get('draw'),
      'recordsTotal' => count($users),
      'recordsFiltered' => User::count(),

      'request' => $request->all(),
    ], 200);
  }

    /**
     * recoverPassword()
     * TODO:Definición
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function recoverPassword(Request $request)
  {
    $sendEmail = $this->sendResetLinkEmail($request);
    return response()->json([
      'message' => $sendEmail['message'],
      'data' => $sendEmail['data'],
      'errors' => $sendEmail['errors'],
    ], $sendEmail['status']);
  }

  public function resetPassword()
  {

  }

  /**
   * sendResetLinkEmail()
   *
   * Send a reset link to the given user.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function sendResetLinkEmail(Request $request)
  {
    $this->validateEmail($request);

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $response = $this->broker()->sendResetLink(
      $request->only('email')
    );

    return $response == Password::RESET_LINK_SENT
      ? $this->sendResetLinkResponse($response)
      : $this->sendResetLinkFailedResponse($request, $response);
  }

  /**
   * sendResetLinkResponse()
   *
   * Get the response for a successful password reset link.
   *
   * @param  string $response
   * @return array
   */
  protected function sendResetLinkResponse($response)
  {
    return [
      'message' => trans($response),
      'data' => null,
      'errors' => null,
      'status' => 200,
    ];
  }

  /**
   * sendResetLinkFailedResponse()
   *
   * Get the response for a failed password reset link.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  string $response
   * @return array
   */
  protected function sendResetLinkFailedResponse(Request $request, $response)
  {
    return [
      'message' => trans($response),
      'data' => null,
      'errors' => [
        'email' => trans($response),
      ],
      'status' => 400,
    ];
  }
}
