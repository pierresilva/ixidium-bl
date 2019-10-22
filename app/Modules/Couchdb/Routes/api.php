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

Route::get('/couchdb', function (Request $request) {
    return response()->json([
        'message' => 'This is the CouchDB module index page.',
    ], 200);
});//->middleware('auth:api');

Route::post('/couchdb/upload', function(Request $request) {

    // dd($request->all());
    try {
        $fileExtension = $request->file('file')->getClientOriginalExtension();
        $fileName = $request->file('file')->getClientOriginalName();
        $request->file('file')->move(
            base_path() . '/public/uploads/test/', $fileName
        );

        return response()->json([
            'message' => 'Imágen subida con exito!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Ocurrio un error!',
            'error' => $e.getMessage(),
        ], 500);
    }

});
Route::resource('/couchdb/contacts', 'ContactsController');