<?php
namespace App\Modules\TransversalSecurity\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\User;

/**
 * @resource Seguridad -  Autenticación
 *
 * Operaciones autenticación aplicativo
 *
 * @author Pierre Silva <pierremichelsilva@gmail.com>
 * @package Seguridad Transversal
 * @version 1.0.0 <2018-05-12>
 */
class AuthController extends Controller
{
  //
  public function __construct()
  { }

  /**
   * login()
   *
   * Método para la autenticación del aplicativo
   *
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
    // $loginData = $request->only(['email', 'password']);

    $loginData['aplicacion'] = env('BASELINE_APLICATION_ID_ST'); // APLICATION ID EN SEGURIDAD TRANSVERSAL;
    $loginData['usuario'] = $request->get('email');
    $loginData['password'] = $request->get('password');

    try {

      $client = new Client();

      $response = $client->post(env('BASELINE_API_WEBSERVICE_ST') . 'SeguridadTransversal/public/api/aplicaciones/seguridadtransversal/logear', [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($loginData),
      ]);

      $userData = json_decode($response->getBody()->getContents())->data;

      if (!isset($userData->id)) {
        return response()->json([
          'message' => 'Credenciales no validas!',
        ], 401);
      }

      $userExists = User::where('name', $userData->usuario)->first();

      if ($userExists) {
        $updateUser = [
          'first_name' => $userData->nombres,
          'last_name' => $userData->apellidos,
          'identification' => $userData->adicionales[0]->documento,
          'profiles' => json_encode($userData->perfiles),
          'actions' => json_encode($userData->acciones),
        ];
        $userExists->update($updateUser);
      }

      if (!$userExists) {
        $newUser = [
          'name' => $userData->usuario,
          'email' => $userData->usuario . '@comfamiliarhuila.com',
          'password' => bcrypt('secret'),
          'identification' => $userData->adicionales[0]->documento,
          'ts_id' => $userData->id,
          'profiles' => json_encode($userData->perfiles),
          'actions' => json_encode($userData->acciones),
          'first_name' => $userData->nombres,
          'last_name' => $userData->apellidos,
          'expire_at' => Carbon::now()->addYears(1)->toDateTimeString(),
        ];
        try {
          User::create($newUser);
        } catch (\Exception $exception) {
          return response()->json([
            'message' => 'Ocurrió un error creando el ususario!',
            'errors' => $exception,
          ], 500);
        }
      }

      try {
        $token = auth()->attempt([
          'email' => $userData->usuario . '@comfamiliarhuila.com',
          'password' => 'secret',
        ]);
      } catch (\Exception $exception) {
        return response()->json([
          'message' => 'Ocurrió un error autenticando al usuario!',
          'errors' => $exception,
        ], 401);
      }

      if (!$token) {
        return response()->json([
          'message' => 'Credenciales no validas!',
          'errors' => [
            'auth' => 'Unauthorized',
          ],
        ], 401);
      }

      return $this->respondWithToken($token);
    } catch (RequestException $re) {

      $userExists = User::where('name', '=', $loginData['usuario'])->first();

      if ($userExists) {
        $token = auth()->attempt([
          'email' => $loginData['usuario'] . '@comfamiliarhuila.com',
          'password' => 'secret',
        ]);
        return $this->respondWithToken($token);
      }

      $message = 'Ocurrio un error haciendo la petición al servidor de seguridad transversal!';

      return response()->json([
        'message' => $message,
        'errors' => [
          'code' => $re->getCode(),
          'message' => $re->getMessage(),
          'file' => $re->getFile(),
          'line' => $re->getLine(),
        ],
      ], 401);
    }
  }

  /**
   * respondWithToken()
   *
   * Método para validar el token del aplicativo
   *
   * @param String $token
   * @return \Illuminate\Http\Response
   */
  private function respondWithToken($token)
  {
    $user = auth()->user();

    $userMeta = $user->toArray();

    // $userMeta['profiles'] = $user->profiles;
    // $userMeta['actions'] = $user->actions;
    $userMeta['token_expire_at'] = now()->addSeconds(auth()->factory()->getTTL() * 60)->toAtomString();

    $userMeta = base64_encode(json_encode($userMeta));

    return response()->json([
      'token' => $token,
      'meta' => $userMeta,
    ]);
  }

  /**
   * signIn()
   *
   * Método para validar las credenciales
   *
   * @param $username
   * @param $password
   * @return \Illuminate\Http\Response
   */
  private function signIn($username, $password)
  {
    if (!$username || $password) {
      return response()->json([
        'message' => 'Credenciales no validas!',
      ], 403);
    }
  }

  /**
   * addUser()
   *
   * Método para validar las credenciales
   *
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function addUser(Request $request)
  {
    // dd('response: ', $request->all());

    $requestData = $request->all();

    if ($request->get('id')) {
      return response()->json([
        'message' => 'Ingreso con éxito!',
        'data' => $requestData,
      ], 200);
    }

    return response()->json([
      'message' => 'Credenciales no validas!',
    ], 403);
  }
}
