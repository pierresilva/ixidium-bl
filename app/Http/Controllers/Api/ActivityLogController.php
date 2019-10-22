<?php

namespace App\Http\Controllers\Api;

use App\LogModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use pierresilva\Activitylog\Models\Activity;

/**
 * @resource Auditoría - Log de Actividades
 *
 * Seguimiento de las actividades de la base de datos
 *
 * Class ActivityLogController
 * @package App\Http\Controllers\Api
 * @author Pierre Silva <pierremichelsilva@gmail.com>
 */
class ActivityLogController extends Controller
{
  //

  /**
   * Items per page.
   *
   * @var int
   */
  public $perPage = 10;

  /**
   * index()
   *
   * Listado de actividades de la base de datos
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request)
  {
    user_can(['audit_access']);

    $keyword = $request->get('search');

    $keyword = ltrim($keyword, '\\');

    if (!empty($keyword)) {
      $activities = Activity::with('causer')
        ->where('log_name', 'LIKE', "%$keyword%")
        ->orWhere('description', 'LIKE', "%$keyword%")
        ->orWhere('subject_type', 'LIKE', "%$keyword%")
        ->orWhere('causer_type', 'LIKE', "%$keyword%")
        ->orWhere('properties', 'LIKE', "%$keyword%")
        ->orWhere('created_at', 'LIKE', "%$keyword%")
        ->orderByDesc('id')
        ->paginate($this->perPage);
    } else {
      $activities = Activity::with('causer')->orderByDesc('id')->paginate($this->perPage);
    }

    $data = $activities->toArray();

    return response()->json([
      'message' => 'Se obtuvo la colecciòn con èxito!',
      'data' => $data['data'],
      'meta' => $this->getMeta($data),
    ], 200);
  }

  /**
   * getModulesModels()
   *
   * Listado de los modelos existentes en los modulos.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getModulesModels()
  {
    $modules = \Module::all();

    $models = [];

    foreach ($modules as $module) {
      if (\File::exists(base_path('app/Modules/' . $module['basename'] . '/Models'))) {
        $files = \File::allFiles(base_path('app/Modules/' . $module['basename'] . '/Models'));

        foreach ($files as $file) {
          if (mb_strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fileName = explode('\\', (string) $file);
          } else {
            $fileName = explode('/', (string) $file);
          }
          $modelName = explode('.', end($fileName));

          $models[] = $module['basename'] . '\Models\\' . $modelName[0];
        }
      }
    }

    $logModels = LogModel::all();

    $removeModels = [];

    foreach ($logModels as $logModel) {
      $modelsCount = 0;
      foreach ($models as $model) {
        if ('\App\Modules\\' . $model === $logModel->fqn) {
          $removeModels[] = $modelsCount;
        }
        $modelsCount++;
      }
    }

    $models = array_values($models);

    foreach ($removeModels as $removeModel) {
      unset($models[$removeModel]);
    }

    $models = array_values($models);

    return response()->json([
      'message' => 'Se obtuvieron los modelos con èxito!',
      'data' => $models,
    ], 200);
  }

  /**
   * getLogModel()
   *
   * Obtiene el listado de las actividades de un determinado modelo
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getLogModel()
  {
    user_can(['audit_list']);

    $logModels = LogModel::with('createdBy', 'updatedBy', 'deletedBy')->get();

    $data = $logModels->toArray();

    return response()->json([
      'message' => 'Se obtuvo la colecciòn con èxito!',
      'data' => $data,
    ], 200);
  }

  /**
   * showLogModel()
   *
   * Obtiene un item del log de actividades por su ID
   *
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function showLogModel($id)
  {
    user_can(['audit_list']);

    $logModel = LogModel::findOrFail($id);

    return response()->json([
      'message' => 'Se obtuvo el item con èxito!',
      'data' => $logModel,
    ], 200);
  }

  /**
   * createLogModel()
   *
   * Comienza el seguimiento de actividades de un modelo
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function createLogModel(Request $request)
  {
    user_can(['audit_create']);

    $logModelData = [];

    $logModelData['fqn'] = '\App\Modules\\' . $request->get('fqn');

    $logModel = LogModel::create($logModelData);

    return response()->json([
      'message' => 'Se creò el item con èxito!',
      'data' => $logModel,
    ], 201);
  }

  /**
   * updateLogModel()
   *
   * Actualiza un item del log de actividades
   *
   * @param Request $request
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateLogModel(Request $request, $id)
  {
    user_can(['audit_update']);

    $logModelData = [];

    $logModelData['fqn'] = '\App\Modules\\' . $request->get('fqn');

    $logModel = LogModel::findOrFail($id);

    $logModel->update($logModelData);

    return response()->json([
      'message' => 'Se actializò el item con èxito!',
      'data' => $logModel,
    ], 202);
  }

  /**
   * deletoLogModel()
   *
   * Detiene el seguimiento de actividades de un determinado modelo
   *
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   * @throws \Exception
   */
  public function deleteLogModel($id)
  {
    user_can(['audit_delete']);

    $logModel = LogModel::findOrFail($id);

    $logModel->delete();

    return response()->json([
      'message' => 'Se eliminò el item con èxito!',
      'data' => $logModel,
    ], 202);
  }

  /**
   * getMeta()
   *
   * Remove data or other key from array
   *
   * @param $data
   * @param string $key
   *
   * @return mixed
   */
  protected function getMeta($data, $key = 'data')
  {
    unset($data[$key]);
    return $data;
  }

  /**
   * getItem()
   *
   * Obtiene un item de determinado modelo por su ID
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getItem(Request $request)
  {
    $modelName = $request->get('model');

    $classModel = new $modelName;

    $item = $classModel::withTrashed()->findOrFail($request->get('id'));

    return response()->json([
      'message' => 'Se obtùvo el item con èxito!',
      'data' => $item,
    ], 200);
  }
}
