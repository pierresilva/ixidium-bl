<?php

use Illuminate\Http\Request;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/users', function (Request $request) {
  return response()->json(\App\User::paginate(10), 200);
});

Route::group([

  'middleware' => 'api',
  'prefix' => 'auth',

], function () {
  // Route::any('users', 'Api\AuthController@getAllUsers')
  //     ->middleware('auth:api', 'has.role:desarrollador', 'has.permission:nivel-desarrollador');
  // Route::post('login', 'Api\AuthController@login');
  // Route::post('register', 'Api\AuthController@register');
  Route::post('logout', 'Api\AuthController@logout');
  Route::post('refresh', 'Api\AuthController@refresh');
  Route::get('me', 'Api\AuthController@me');
  // Route::post('recover-password', 'Api\AuthController@recoverPassword');
  // Route::post('change-password', 'Api\AuthController@changePassword');

});

Route::get('audit/activities', 'Api\ActivityLogController@index');
Route::post('audit/activities/item', 'Api\ActivityLogController@getItem');
Route::get('audit/activities/get-models-list', 'Api\ActivityLogController@getModulesModels');
Route::get('audit/activities/models', 'Api\ActivityLogController@getLogModel');
Route::get('audit/activities/models/{id}', 'Api\ActivityLogController@showLogModel');
Route::post('audit/activities/models', 'Api\ActivityLogController@createLogModel');
Route::put('audit/activities/models/{id}', 'Api\ActivityLogController@updateLogModel');
Route::delete('audit/activities/models/{id}', 'Api\ActivityLogController@deleteLogModel');

//Route::get('test/pdf', 'PdfController@makePdf');

//Route::get('test/service-order-pdf', 'ServiceOrderPdfController@makePdf');

//Route::get('festivos/{year}', 'HollydaysController@getHollydays');
//Route::get('festivo/{d}/{m}', 'HollydaysController@getHollyday');

//Route::get('demo-email', 'MailController@sendDemoEmail');

//Route::get('holydays/sample', 'BusinessDaysController@index');


Route::get('validate-slug', function (Request $request) {

  $slug = \App\Helpers\SlugHelper::findSlugs(
    $request->get('value'),
    $request->get('table') ,
    $request->get('column'),
    \DB::connection()->getDriverName()
  );

  $existe = count($slug);

  return response()->json([
    'message'=> !$existe ? 'Slug Ok' : 'Slug Existe',
    'data' => !$existe ? true : false,
    'slug' => $request->get('value')
  ], 200);

});

Route::get('/users/all', function(Request $request) {
  $users = \App\User::select('id', 'name', 'email', 'some')->paginate(10)->toArray();

  return $users['data'];
});

Route::get('test/send-print', 'HomeController@sendEmail');
//Route::get('test/calculate-tax', 'HomeController@calculateTax');

/*
Route::get('test/parameter-detail', function() {
  $detail = get_detail_parameters('pagos-en-linea', 'minimo-cotizaciones')[0];
  dd($detail);
});*/

Route::get('user/test', function(Request $request) {

  return response()->json([
    'message' => 'No autorizado!'
  ], 401);
});

Route::get('days/test', function(Request $request) {
  $businessDays = new \App\Helpers\BusinessDays();
  $businessDays->setWeekendDays([
    Carbon::SATURDAY,
    Carbon::SUNDAY
  ]);

  // dd($businessDays->daysBetween(\Carbon\Carbon::parse('2019-08-02'), \Carbon\Carbon::parse('2019-08-05')));

  $dateFrom = Carbon::parse('2019-07-19 08:00:00');
  $dateTo = Carbon::parse('2019-07-22 18:00:00');

  $businessDays->addClosedDay(Carbon::parse('2019-08-05'));

  dd($businessDays->daysBetween(Carbon::parse('2019-08-12'), Carbon::parse('2019-08-16'), true));

  dd($businessDays->hoursBetween($dateFrom, $dateTo));

  dd($businessDays->nextBusinessDay(Carbon::parse('2019-08-02'))->toDateTimeString());

});


Route::get('prueba/sigas', function (Request $request) {

  $list = [];

  $list[] = array(
    'TipoDocAfiliado' => 'CC',
    'DocAfiliado' => '36311124',
  );

  $content = array(
    "appId" => '71226407dc4692ac0ffd7f06f0bace3c',
    "appPwd" => '7fbeca3a65badf9ca19bd08239bf2a25',
    "reqBeneficiario" => 'S',
    "afiliados" => base64_encode(json_encode($list)),
  );

  $ch = curl_init();

  $vars = http_build_query($content);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_URL, config('sigas.api_url') . "/APIRESTSigas/api/ConsultaTrabajadorGF/ConsultarAfiliadoGF?" . $vars);

  $response = curl_exec($ch);

  curl_close($ch);

  if (!$response) {
    return response()->json([
      'message' => 'No hay data!'
    ], 404);
  } else {
    return response()->json([
      'message' => 'Se encontro data!',
      'data' => json_decode($response),
    ], 200);
  }
});

Route::post('/run-command', function(Request $request) {
  Debugbar::disable();
  $output = shell_exec('cd ' . base_path() . ' && ' . $request->get('command'));
  return response()->json([
    'message' => 'Comando ejecutado!',
    'data' => '<pre>' . $output . '</pre>'
  ], 200);
});
