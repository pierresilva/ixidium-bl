<?php

namespace App\Modules\Reporter\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reporter\Http\Requests\ReportsCreateRequest;
use App\Modules\Reporter\Http\Requests\ReportsEditRequest;
use App\Modules\Reporter\Models\RepoReport;
use Illuminate\Http\Request;

/**
 * @resource Reporteador - Reporte
 *
 * Operaciones para el proceso de categorías de los reportes
 *
 * @author Pierre Michel Silva <pierremichelsilva@gmail.com>
 * @package App\Modules\Reporter\Http\Controllers
 * @version 1.0.0 <2018-12-18>
 */
class ReportsController extends Controller
{
  /**
   * index()
   *
   * Despliega un listado de los reportes generados
   *
   * @response {
   *     message: string,
   *     data: RepoReport[],
   *     meta: Meta
   * }
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    //
    $keyword = $request->get('search');

    $reports = RepoReport::where('created_at', '<>', null);

    if ($keyword) {
      $reports->where('name', 'LIKE', '%' . $keyword . '%')
        ->orWhere('connection', 'LIKE', '%' . $keyword . '%')
        ->orWhere('start_at', 'LIKE', '%' . $keyword . '%')
        ->orWhere('end_at', 'LIKE', '%' . $keyword . '%')
        ->orWhere('sql', 'LIKE', '%' . $keyword . '%')
        ->orWhere('fields', 'LIKE', '%' . $keyword . '%')
        ->orWhere('options', 'LIKE', '%' . $keyword . '%');
    }

    $data = $reports->paginate(10)->toArray();

    return response()->json([
      'message' => count($data['data']) ? 'Reportes obtenidos con éxito!' : 'No hay reportes disponibles!',
      'data' => $data['data'],
      'meta' => $this->getMeta($data),
    ], 200);

  }

  /**
   * create()
   *
   * Show the form for creating a new resource.
   *
   * @hideFromAPIDocumentation
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * store()
   *
   * Almacena el reporte correspondiente
   *
   * @response {
   *     message: string,
   *     data: RepoReport
   * }
   *
   * @param ReportsCreateRequest $request
   * @return \Illuminate\Http\Response
   */
  public function store(ReportsCreateRequest $request)
  {

    $validateViewName = substr($request->get('name'), 0,4);

    if (mb_strtolower($validateViewName) !== 'view') {
      return response()->json([
        'message' => 'El nombre del reporte debe iniciar con "view"',
        'errors' => [
          'report_name' => 'EJ: View Process'
        ]
      ], 422);
    }

    //
    try {
      $this->createView($request->get('connection'), $request->get('name'), $request->get('sql'));
    } catch (\Exception $exception) {
      return response()->json([
        'message' => 'Ocurrio un error creando la vista!',
        'errors' => [
          'message' => $exception->getMessage(),
        ],
      ], 500);
    }

    $requestData = $request->all();

    $requestData['fields'] = json_encode($request->get('fields'), true);

    $requestData['start_at'] = date('H:i:s.u', strtotime($request->get('start_at')));
    $requestData['end_at'] = date('H:i:s.u', strtotime($request->get('end_at')));

    $newReport = RepoReport::create($requestData);

    return response()->json([
      'message' => 'Reporte creado con éxito!',
      'data' => $newReport,
    ], 201);

  }

  /**
   * show()
   *
   * Visualiza la información del reporte a partir del identificador ID
   *
   * @response {
   *     message: string,
   *     data: RepoReport
   * }
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
    $report = RepoReport::findOrFail($id);

    return response()->json([
      'message' => 'Reporte obtenido con éxito!',
      'data' => $report,
    ], 200);
  }

  /**
   * edit()
   *
   * Edita la información del reporte a partir del identificador ID
   *
   * @hideFromAPIDocumentation
   *
   * @param  int $id
   *
   * @return void
   */
  public function edit($id)
  {
    //
  }

  /**
   * update()
   *
   * Actualiza la información del reporte a partir del identificador ID
   *
   * @response {
   *    message: string,
   *    data: RepoReport
   * }
   *
   * @param ReportsEditRequest $request
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function update(ReportsEditRequest $request, $id)
  {
    //

    $report = RepoReport::findOrFail($id);

    $requestData = $request->all();

    try {
      $this->alterView($request->get('connection'), $request->get('name'), $request->get('sql'));
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ocurrio un error actualizando la vista!',
        'errors' => [
          'message' => $e->getMessage(),
          'file' => $e->getFile(),
          'line' => $e->getLine(),
        ]
      ], 403);
    }

    $requestData['start_at'] = date('H:i:s.u', strtotime($request->get('start_at')));
    $requestData['end_at'] = date('H:i:s.u', strtotime($request->get('end_at')));

    $requestData['fields'] = json_encode($request->get('fields'), true);

    $report->update($requestData);

    return response()->json([
      'message' => 'Reporte actualizado con éxito!',
      'data' => $report,
    ], 202);

  }

  /**
   * destroy()
   *
   * Elimina la información del reporte a partir del identificador ID
   *
   * @response {
   *     message: string,
   *     data: RepoReport
   * }
   *
   * @param Request $request
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   *
   * @throws \Exception
   */
  public function destroy($id)
  {
    //
    $report = RepoReport::findOrFail($id);

    try {
      $this->dropView($report->connection, $report->name);
    } catch (\Exception $exception) {
      return response()->json([
        'message' => 'Ocurrio un error eliminando la vista!',
        'errors' => [
          'message' => $exception->getMessage(),
        ],
      ], 500);
    }

    $report->delete();

    return response()->json([
      'message' => 'Reporte eliminado con éxito!',
      'data' => $report,
    ], 202);

  }

  /**
   * createView()
   *
   * Crear vista en la base de datos
   *
   * @param string $connection
   * @param string $name
   * @param string $sql
   *
   * @return bool
   */
  public function createView(string $connection, string $name, string $sql)
  {

    $parsedName = snake_case($name);

    try {
      if ($connection == 'mysql') {
        $statement = "CREATE VIEW ". $parsedName ." AS " . $sql;
        \DB::connection($connection)->statement($statement);
      }
      if ($connection == 'sqlsrv') {
        $statement = "CREATE VIEW \"". $parsedName ."\" AS " . $sql;
        \DB::connection($connection)->statement($statement);
      }
      if ($connection != 'mysql' || $connection != 'sqlsrv') {
        return response()->json([
          'message' => 'Base de datos no soportada!'
        ], 422);
      }
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  /**
   * alterView()
   *
   * Validar SQL con la base de datos
   *
   * @param string $connection
   * @param string $name
   * @param string $sql
   * @return bool
   */
  public function alterView(string $connection, string $name, string $sql)
  {
    $parsedName = snake_case($name);

    try {
      if ($connection == 'mysql') {
        $statement = "ALTER VIEW ". $parsedName ." AS " . $sql;
        \DB::connection($connection)->statement($statement);
      }
      if ($connection == 'sqlsrv') {
        $statement = "ALTER VIEW \"". $parsedName ."\" AS " . $sql;
        \DB::connection($connection)->statement($statement);
      }
      if ($connection != 'mysql' || $connection != 'sqlsrv') {
        return response()->json([
          'message' => 'Base de datos no soportada!'
        ], 422);
      }
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  /**
   * dropView()
   * Eliminar vista en la base de datos
   *
   * @param string $connection
   * @param string $name
   *
   * @return bool
   */
  public function dropView(string $connection, string $name)
  {
    $parsedName = kebab_case($name);

    try {
      if ($connection == 'mysql') {
        $statement = "DROP VIEW ". $parsedName;
        \DB::connection($connection)->statement($statement);
      }
      if ($connection == 'sqlsrv') {
        $statement = "DROP VIEW \"". $parsedName ."\"";
        \DB::connection($connection)->statement($statement);
      }
      if ($connection != 'mysql' || $connection != 'sqlsrv') {
        return response()->json([
          'message' => 'Base de datos no soportada!'
        ], 422);
      }
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  /**
   * checkSql()
   *
   * Validar resultados sentencia SQL con la base de datos
   *
   * @param Request $request
   *
   * @return \Response
   */
  public function checkSql(Request $request)
  {

    $connection = $request->get('connection') ? $request->get('connection') : 'sqlsrv';

    $sql = $request->get('sql');

    if ($sql) {

      if ($this->validateSql($request->get('sql'), $connection)) {
        $data = collect(\DB::connection($connection)->select($sql))->first();

        if (null === $data) {
          return response()->json([
            'message' => 'La sentencia SQL no obtuvo resultados!'
          ], 400);
        }

        return response()->json([
          'message' => 'Sentencia SQL valida!',
          'data' => $data,
        ]);
      }
    }

    return response()->json([
      'message' => 'Debe especificar una sentencia SQL valida!',
    ], 422);

  }

  /**
   * validateSql()
   *
   * Validar sentencia SQL con la base de datos
   *
   * @param string $sql
   * @param string $connection
   *
   * @return boolean
   */
  public function validateSql(string $sql, string $connection): bool
  {
    if (preg_match("/\bdelete\b/i", $sql) ||
      preg_match("/\bupdate\b/i", $sql) ||
      preg_match("/\binsert\b/i", $sql)) {
      return false;
    }
    try {
      collect(\DB::connection($connection)->select($sql))->first();
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

    /**
     * getOptions()
     *
     * Obtiene la vista del reporte correspondiente
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
  public function getOptions($id)
  {
    $report = RepoReport::findOrFail($id);

    $fields = json_decode($report->fields);

    $parsedName = snake_case($report->name);

    $reportFields = [];

    foreach ($fields as $field) {
      $reportFields[] = [
        'data' => $field->name,
        'title' => $field->title,
        'type' => $field->filter,
        'totalize' => $field->totalize
      ];
    }
    $data = [];

    $data['ajax']['url'] = config('app.url') . '/api/reporter/datatables/' . $report->connection . '/' . $parsedName;
    $data['pagingType'] = 'full_numbers';
    $data['pageLength'] = 10;
    $data['serverSide'] = true;
    $data['processing'] = true;
    $data['searching'] = true;
    $data['search']['regex'] = true;
    $data['language']['lengthMenu'] = 'Mostrando _MENU_ registros por página';
    $data['language']['zeroRecords'] = 'No se encontraron resultados';
    $data['language']['info'] = 'Mostrando _START_ a _END_ de _TOTAL_ registros';
    $data['language']['emptyTable'] = 'Mostrando 0 a 0 de 0 registros';
    $data['language']['infoFiltered'] = '(filtrados de _MAX_ registros en total)';
    $data['language']['search'] = 'Buscar:';
    $data['language']['processing'] = 'Procesando...';
    $data['language']['loadingRecords'] = 'Cargando...';
    $data['language']["paginate"] = [
      "first" => "Primero",
      "last" => "Último",
      "next" => "Siguiente",
      "previous" => "Anterior"
    ];
    $data['columns'] = $reportFields;
    $data['dataSrc'] = '#function (json) { console.log(json); }#';
    $data['dom'] = 'Bfrtip';
    $data['buttons'] = [];
    // $data['initComplete'] = '#() => { setInterval(() => { console.log(\'Loggeando\'); }, 5000); }#';
    $jsonData = json_encode($data);
    $string = str_replace('"#', '', $jsonData);
    $string = str_replace('#"', '', $string);
    $string = str_replace('\"', '\\', $string);

    return response()->json([
      'message' => 'Se obtuvo la configuración con éxito!',
      'data' => $data,
    ]);
  }
}
