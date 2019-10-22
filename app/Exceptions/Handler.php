<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
    \Illuminate\Validation\ValidationException::class,
  ];

  protected $message = 'Error no determinado';

  protected $statusCode = 400;

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * RepoReport or log an exception.
   *
   * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
   *
   * @param  \Exception $exception
   * @return void
   * @throws Exception
   */
  public function report(Exception $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Exception $exception
   * @return \Illuminate\Http\Response
   */
  public function render($request, Exception $exception)
  {

    $json = [
      'message' => $exception->getMessage() ? $exception->getMessage() : $this->message,
      'errors' => env('BASELINE_APP_DEBUG') ? [
        'code' => $exception->getCode() ? 'CÓDIGO: ' . $exception->getCode() : 'CÓDIGO: ' . $this->statusCode,
        'message' => $exception->getMessage() ? 'MENSAJE: ' . $exception->getMessage() : 'MENSAJE: ' . $this->message,
        'file' => 'ARCHIVO: ' . $exception->getFile(),
        'line' => 'LÍNEA: ' . $exception->getLine(),
      ] : null,
    ];

    if (!config('app.debug')) {

    }

    if (\Request::is('api/*') && $exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError) {
      $this->message = 'Error fatal.';
      $this->statusCode = 500;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
      $this->message = 'To many request.';
      $this->statusCode = 429;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
      $this->message = 'Método no permitido para esta petición.';
      $this->statusCode = 405;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
      $this->message = 'Página no encontrada.';
      $this->statusCode = 404;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
      $this->message = 'No existen resultados para la consulta.';
      $this->statusCode = 404;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
      $this->message = 'Su token expiró.';
      $this->statusCode = 401;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
      $this->message = 'Su token no es valido.';
      $this->statusCode = 401;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
      $this->statusCode = 401;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
      $this->message = 'Su token esta en la lista negra.';
      $this->statusCode = 401;
      return response()->json($json, $exception->getCode() ? $exception->getCode() : $this->statusCode);
    }

    if (\Request::is('api/*') && $exception instanceof \Illuminate\Database\QueryException) {
        $this->message = 'Error de base de datos.';
        $this->statusCode = 500;
        return response()->json($json, 500);
    }

    if (\Request::is('api/*') && $exception instanceof \Illuminate\Validation\ValidationException) {
      $this->message = 'Existen errores en el formulario.';
      $json['errors'] = $exception->errors();
      $this->statusCode = 422;
      return response()->json($json, $this->statusCode);
  }

    return parent::render($request, $exception);
  }
}
