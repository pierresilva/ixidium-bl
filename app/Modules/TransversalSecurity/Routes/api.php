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

Route::get('/transversal-security', function (Request $request) {
    return response()->json([
        'message' => 'This is the TransversalSecurity module index page.',
    ], 200);
});//->middleware('auth:api');

Route::post('/transversal-security/login', 'AuthController@login');

Route::post('/transversal-security/add-user', 'AuthController@addUser');
