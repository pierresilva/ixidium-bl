<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/reporter', function (Request $request) {

    if ($this->app->environment() == 'local') {
        return [
            'env' => $this->app->environment()
        ];
    }

    return response()->json([
        'message' => 'This is the Reporter module index page.',

    ], 200);
});//->middleware('auth:api');

Route::post('reporter/get-csv/{connection}/{view}/{id}', 'CategoriesController@getCsv');
Route::post('reporter/get-pdf/{connection}/{view}/{id}', 'CategoriesController@getPdf');
Route::any('reporter/get-table-fields-info/{connection}/{table}', 'CategoriesController@getTableFieldsInfo');
Route::post('reporter/datatables/{connection}/{table}', 'CategoriesController@dataTables');
Route::get('reporter/datatables/{connection}/{table}', 'CategoriesController@dataTables');

Route::get('reporter/connections', 'SqlController@getConnections');
Route::get('reporter/dynamic-model', 'SqlController@dynamicModel');
Route::get('reporter/modules', 'SqlController@getModules');

Route::get('reporter/reports/options/{id}', 'ReportsController@getOptions');
Route::post('reporter/check-sql', 'ReportsController@checkSql');
Route::get('reporter/reports', 'ReportsController@index');
Route::get('reporter/reports/{id}', 'ReportsController@show');
Route::post('reporter/reports', 'ReportsController@store');
Route::put('reporter/reports/{id}', 'ReportsController@update');
Route::delete('reporter/reports/{id}', 'ReportsController@destroy');

