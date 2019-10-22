<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/*
Route::get('/', function () {
    return view('welcome');
});
 */

Route::get('/elfinder/popup', '\Barryvdh\Elfinder\ElfinderController@showPopup');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/refresh/database', function () {

  $exitcode = \Artisan::call('migrate:refresh', [
    '--seed' => true,
  ]);

  return response()->json([
    'message' => 'OK',
    'data' => $exitcode,
  ]);
});

Route::get('doc-schema', function (Illuminate\Http\Request $request) {
  $schema = UniSharp\DocUs\Parser::getSchema();

  return response()->view("schema.views.{$request->get('format')}", compact('schema'))
    ->header('Content-Type', "text/{$request->get('format')}");
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('adminier/{usr}/{pw}', function ($usr, $pw) {

  if ($usr === '010101' && $pw === '101010' && env('BASELINE_APP_ENV') != 'production') {

    $data = [
      'server' => env('BASELINE_DB_HOST'),
      'username' => env('BASELINE_DB_USERNAME'),
      'db' => env('BASELINE_DB_DATABASE'),
      'password' => env('BASELINE_DB_PASSWORD'),
      'script' => 'db'
    ];

    $url = env('BASELINE_APP_URL') . '/adminier.php?server=' . $data['server'] . '&username=' . $data['username'] . '&db=' . $data['db'] . '&password=' . $data['password'] . '&script=db';

    return \Redirect::to($url);
  } else {

    echo 'Fuck you!';
  }
});

Route::any('{path?}', function () {
  try {
    $index = File::get(public_path() . '/dist/index.html');
  } catch (Exception $e) {
    return response()->json([
      'message' => 'No exste la aplicación angular!',
    ], 404);
  }

  if (env('BASELINE_APP_ENV') != 'production') {
    \Debugbar::disable();
    $jiraReportScript = '<script type="text/javascript" src="https://comfamiliar-huila.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/5epbf3/b/17/a44af77267a987a660377e5c46e0fb64/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=es-ES&collectorId=fe162dfa"></script>';
    $index = str_replace('</body>', "\n  {$jiraReportScript}\n</body>", $index);
    // $index = str_replace('<base href="/">', '<base href="/dist/">', $index);
  }

  return $index;
})->where("path", ".+");
