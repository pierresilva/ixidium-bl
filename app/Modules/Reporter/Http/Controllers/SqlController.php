<?php

namespace App\Modules\Reporter\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

/**
 * @resource Reporteador - SQL
 *
 *
 * Clase para manipular sentencias SQL
 *
 * @author Pierre Michel Silva <pierremichelsilva@gmail.com>
 * @package App\Modules\Reporter\Http\Controllers
 * @version 1.0.0 <2018-12-18>
 */
class SqlController extends Controller
{
    //
    /**
     * checkSql()
     *
     * Comprueba que una sentencia sql para una vista sea valida.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSql(Request $request)
    {

        $connection = $request->get('connection') ? $request->get('connection') : 'sqlsrv';

        $sql = $request->get('sql');

        if ($sql) {

            if (preg_match("/\bdelete\b/i", $sql) ||
                preg_match("/\bupdate\b/i", $sql) ||
                preg_match("/\binsert\b/i", $sql)) {
                return response()->json([
                    'message' => 'La sentencia SQL solo puede ser una consulta de selección!',
                    'data' => [],
                ], 403);
            }

            try {
                $response = collect(\DB::connection($connection)->select($sql))->first();

                return response()->json([
                    'message' => 'La sentencia SQL es valida!',
                    'data' => $response,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'La sentencia SQL es invalida!',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Debe especificar una sentencia SQL',
        ], 422);


    }

    /**
     * getConnections()
     *
     * Retorna un listadod de las conexiones del sistema
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConnections()
    {
        $connections = config('database.connections');

        return response()->json([
            'message' => 'Se obtubieron las conexines con éxito!',
            'data' => $connections
        ]);
    }

    /**
     * getModules()
     *
     * Retorna un listado de los modulos del sistema
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModules()
    {
        return response()->json([
            'message' => 'Modulos obtenidos con éxito!',
            'data' => \Module::all(),
        ]);
    }

    /**
     * dynamicModel()
     *
     * Retorna un recurso paginado a traves de un modelo dinamico
     *
     * @return mixed
     */
    public function dynamicModel()
    {
        $view = 'order details extended';

        $modelName = studly_case($view);

        $nameSpacedModel = '\\App\Modules\Reporter\Models\\' . $modelName;

        $items = $nameSpacedModel::paginate(10);

        return $items;


    }
}
