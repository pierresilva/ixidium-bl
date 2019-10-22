<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Collection;
use App\Renova\Paginate;
use App\Renova\Transformer;

class ApiController extends Controller
{
  /**
   * Transformer
   *
   * @var null
   */
  protected $transformer = null;

  protected $perPage = 10;

  /**
   * Return generic json response with the given data.
   *
   * @param $data
   * @param int $statusCode
   * @param array $headers
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respond($data, $statusCode = 200, $headers = [])
  {
    return response()->json($data, $statusCode, $headers);
  }

  /**
   * Respond with data after applying transformer.
   *
   * @param $data
   * @param int $statusCode
   * @param array $headers
   * @return \Illuminate\Http\JsonResponse
   * @throws Exception
   */
  protected function respondWithTransformer($data, $statusCode = 200, $headers = [])
  {
    $this->checkTransformer();

    if ($data instanceof Collection) {
      $data = $this->transformer->collection($data);
    } else {
      $data = $this->transformer->item($data);
    }

    return $this->respond($data, $statusCode, $headers);
  }

  /**
   * Respond with pagination.
   *
   * @param $paginated
   * @param int $statusCode
   * @param array $headers
   * @return \Illuminate\Http\JsonResponse
   * @throws Exception
   */
  protected function respondWithPagination($paginated, $statusCode = 200, $headers = [])
  {
    $this->checkPaginated($paginated);

    $this->checkTransformer();

    $data = $this->transformer->paginate($paginated);

    return $this->respond($data, $statusCode, $headers);
  }

  /**
   * Respond with success.
   *
   * @param null $data
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondSuccess($data = null)
  {
    return $this->respond($data);
  }

  /**
   * Respond with created.
   *
   * @param $data
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondCreated($data)
  {
    return $this->respond($data, 201);
  }

  /**
   * Respond with no content.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondNoContent()
  {
    return $this->respond(null, 204);
  }

  /**
   * Respond with error.
   *
   * @param $message
   * @param $statusCode
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondError($message, $statusCode, $errors = [])
  {
    return $this->respond([
      'message' => $message,
      'status_code' => $statusCode,
      'errors' => ($statusCode == 422 || ($statusCode >= 400 && env('BASELINE_APP_ENV') == 'local')) ? $errors : [],
    ], $statusCode);
  }

  /**
   * Respond with unauthorized.
   *
   * @param string $message
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondUnauthorized($message = 'Unauthorized')
  {
    return $this->respondError($message, 401);
  }

  /**
   * Respond with forbidden.
   *
   * @param string $message
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondForbidden($message = 'Forbidden')
  {
    return $this->respondError($message, 403);
  }

  /**
   * Respond with not found.
   *
   * @param string $message
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondNotFound($message = 'Not Found')
  {
    return $this->respondError($message, 404);
  }

  /**
   * Respond with failed login.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondFailedLogin()
  {
    return $this->respond([
      'errors' => [
        'email or password' => 'is invalid',
      ]
    ], 422);
  }

  /**
   * Respond with internal error.
   *
   * @param string $message
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondInternalError($message = 'Internal Error')
  {
    return $this->respondError($message, 500);
  }

  /**
   * Check if valid transformer is set.
   *
   * @throws Exception
   */
  private function checkTransformer()
  {
    if ($this->transformer === null || !$this->transformer instanceof Transformer) {
      throw new Exception('Invalid data transformer.');
    }
  }

  /**
   * Check if valid paginate instance.
   *
   * @param $paginated
   * @throws Exception
   */
  private function checkPaginated($paginated)
  {
    if (!$paginated instanceof Paginate) {
      throw new Exception('Expected instance of Paginate.');
    }
  }
}
