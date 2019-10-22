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

Route::get('/DummySlug', function (Request $request) {
    return response()->json([
        'message' => 'This is the DummyName module index page.',
    ], 200);
});//->middleware('auth:api');
