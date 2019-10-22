<?php

namespace App\Modules\Reporter\Http\Controllers;

use App\Modules\Reporter\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exporter;
use PHPUnit\Runner\Exception;

/**
 * @resource Reporteador - Categorías
 *
 * Operaciones para el proceso de categorías de los reportes
 *
 * @author Pierre Michel Silva <pierremichelsilva@gmail.com>
 * @package App\Modules\Reporter\Http\Controllers
 * @version 1.0.0 <2018-12-18>
 */
class CategoriesController extends Controller
{
  /**
   * index()
   *
   * Visualiza el listado de categorías
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //

    $categories = Category::paginate(10);

    $dataSet = $categories->toArray();

    $count = 0;
    foreach ($dataSet['data'] as $data) {
      // $dataSet['data'][$count]['Picture'] = utf8_encode($data['Picture']);
      unset($dataSet['data'][$count]['Picture']);
      $count++;
    }

    return response()->json([
      'message' => 'Se obtubo la colección con èxito!',
      'data' => $dataSet['data'],
      'meta' => $this->getMeta($dataSet),
    ], 200, ['Content-type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  /**
   * dataTables
   *
   * Retorna la consulta en el formato infdicado para el plugin datatables.
   *
   * @param Request $request
   * @param $connection
   * @param $table
   * @return mixed
   * @throws \Exception
   */
  public function dataTables(Request $request, $connection, $table)
  {

    $reportColumns = json_decode($request->get('report_columns'));

    $cols = [];

    $totals = [];

    foreach ($request->get('columns') as $col) {
      $cols[] = $col['data'];
    }

    $database = \DB::connection($connection)
      ->table($table)
      ->select($cols);

    $requestData = $request->all();

    foreach ($requestData['columns'] as $data) {
      if (null !== $data['search']['value'] && '' !== $data['search']['value']) {
        $dataSearch = str_replace('\\', '', $data['search']['value']);
        $searchData = explode('|', $dataSearch);
        // dd($searchData);
        if (null !== @$searchData[1] && null !== @$searchData[2]) {
          // dd($searchData);
          $database->whereBetween($data['data'], [$searchData[1], $searchData[2]]);
        } elseif (null !== @$searchData[1]) {
          // dd($searchData);
          $database->where($data['data'], $searchData[0], $searchData[1]);
        } elseif (null === @$searchData[1] && null === @$searchData[2]) {
          // dd($searchData);
          $database->where($data['data'], 'LIKE', '%' . $searchData[0] . '%');
        }
      }
    }

    $reportColumnsCnt = 0;

    foreach ($reportColumns as $reportColumn) {
      if ($reportColumn->totalize == 'yes') {
        $totals[] = [
          'column' => $reportColumnsCnt,
          'field' => $reportColumn->name,
          'title' => $reportColumn->title,
          'total' => $database->sum($reportColumn->name),
        ];
      }
      $reportColumnsCnt++;
    }

    $datatables = \DataTables::of($database);

    foreach ($requestData['columns'] as $data) {
      if (null !== $data['search']['value'] && '' !== $data['search']['value']) {
        $dataSearch = str_replace('\\', '', $data['search']['value']);
        $searchData = explode('|', $dataSearch);
        if (null !== @$searchData[1] && null !== @$searchData[2]) {
          // dd($searchData);
          $database->orWhereBetween($data['data'], [$searchData[1], $searchData[2]]);
        } elseif (null !== @$searchData[1]) {
          // dd($searchData);
          $database->orWhere($data['data'], $searchData[0], $searchData[1]);
        } elseif (null === @$searchData[1] && null === @$searchData[2]) {
          // dd($searchData);
          $database->orWhere($data['data'], 'LIKE', '%' . $searchData[0] . '%');
        }
      }
    }

    $dt = $datatables->make(true);

    $response = $dt->original;
    $response['totals'] = $totals;

    return $response;
  }

  /**
   * getTableFieldsInfo()
   *
   * Retorna la información de los campos de una consulta en el formato para el plugin datatables.
   *
   * @param $connection
   * @param $table
   * @return \Illuminate\Http\JsonResponse
   */
  public function getTableFieldsInfo($connection, $table)
  {
    $columns = \DB::connection($connection)->getSchemaBuilder()->getColumnListing($table);

    $columnsData = [];

    $count = 0;
    foreach ($columns as $column) {
      $columnsData[$count]['name'] = $column;
      $columnsData[$count]['type'] = \DB::connection($connection)->getSchemaBuilder()->getColumnType($table, $column);
      $count++;
    }

    return response()->json($columnsData);
  }

  /**
   * getCsv()
   *
   * Permite exportar la data en formato csv
   *
   * @param Request $request
   * @param $connection
   * @param $view
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getCsv(Request $request, $connection, $view, $id)
  {
    $cols = [];

    foreach ($request->get('columns') as $col) {
      $cols[] = $col['data'];
    }

    $requestDatatable = $request->get('datatable');

    $data = \DB::connection($connection)
      ->table(snake_case($view))
      ->select($cols);

    foreach ($requestDatatable['columns'] as $col) {
      if (null !== $col['search']['value'] && '' !== $col['search']['value']) {
        $dataSearch = str_replace('\\', '', $col['search']['value']);
        $searchData = explode('|', $dataSearch);
        if (null !== @$searchData[1] && null !== @$searchData[2]) {
          $data->orWhereBetween($col['data'], [$searchData[1], $searchData[2]]);
        } elseif (null !== @$searchData[1]) {
          $data->orWhere($col['data'], $searchData[0], $searchData[1]);
        } elseif (null === @$searchData[1] && null === @$searchData[2]) {
          $data->orWhere($col['data'], 'LIKE', '%' . $searchData[0] . '%');
        }

      }
    }

    $reportColumns = json_decode($requestDatatable['report_columns']);

    $reportColumnsCnt = 0;

    foreach ($reportColumns as $reportColumn) {
      if ($reportColumn->totalize == 'yes') {
        $totals[] = [
          'column' => $reportColumnsCnt,
          'field' => $reportColumn->name,
          'title' => $reportColumn->title,
          'total' => $data->sum($reportColumn->name),
        ];
      }
      $reportColumnsCnt++;
    }

    $file = 'reports/report_[' . kebab_case($view) . ']_' . md5(date('Y-m-d H:i:s')) . '.xlsx';

    try {
      $serialiser = new ReportSerialiser($request->get('columns'));
      $excel = Exporter::make('Excel');
      $excel->load($data->get());
      $excel->setSerialiser($serialiser);
      $excel->save($file);
    } catch (\Exception $exception) {

      return response()->json([
        'message' => $exception->getMessage(),
      ], 400);

    }

    return response()->json([
      'message' => 'Repporte generado con éxito!',
      'data' => $file,
    ]);
  }

  /**
   * getPdf()
   *
   * Permite exportar la data en formato pdf
   *
   * @param Request $request
   * @param $connection
   * @param $view
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getPdf(Request $request, $connection, $view, $id)
  {
    $cols = [];

    $totals = [];

    foreach ($request->get('columns') as $col) {
      $cols[] = $col['data'];
    }

    $requestDatatable = $request->get('datatable');

    $data = \DB::connection($connection)
      ->table(snake_case($view))
      ->select($cols);

    foreach ($requestDatatable['columns'] as $col) {

      if (null !== $col['search']['value'] && '' !== $col['search']['value']) {
        $dataSearch = str_replace('\\', '', $col['search']['value']);
        $searchData = explode('|', $dataSearch);
        if (null !== @$searchData[1] && null !== @$searchData[2]) {
          $data->orWhereBetween($col['data'], [$searchData[1], $searchData[2]]);
        } elseif (null !== @$searchData[1]) {
          $data->orWhere($col['data'], $searchData[0], $searchData[1]);
        } elseif (null === @$searchData[1] && null === @$searchData[2]) {
          $data->orWhere($col['data'], 'LIKE', '%' . $searchData[0] . '%');
        }
      }

    }

    $reportColumns = json_decode($requestDatatable['report_columns']);

    $reportColumnsCnt = 0;

    foreach ($reportColumns as $reportColumn) {
      if ($reportColumn->totalize == 'yes') {
        $totals[] = [
          'column' => $reportColumnsCnt,
          'field' => $reportColumn->name,
          'title' => $reportColumn->title,
          'total' => $data->sum($reportColumn->name),
        ];
      }
      $reportColumnsCnt++;
    }


    $file = 'reports/report_[' . kebab_case($view) . ']_' . md5(date('Y-m-d H:i:s')) . '.pdf';

    $viewData = [
      'report_name' => $view,
      'data' => $data->get(),
      'cols' => $reportColumns,
      'totals' => $totals,
    ];

    try {
      $pdf = \PDF::loadView('pdf.report', $viewData);
      $pdf->setPaper('letter', 'landscape');
      $pdf->save($file);
    } catch (\Exception $exception) {
      // dd($exception->getMessage());
    }

    return response()->json([
      'message' => 'Repporte generado con éxito!',
      'data' => $file,
    ]);

  }

}
