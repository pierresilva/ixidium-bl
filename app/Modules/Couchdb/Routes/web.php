<?php
use App\Modules\Couchdb\Models\Contact;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'couchdb'], function () {

    Route::get('/', function () {
        dd('This is the CouchDB module index page!');
    });

    Route::get('test', function() {
      $contact = Contact::create([
        'name' => 'Contact Name',
        'address' => 'Calle 22 # 33-44',
        'phone' => '8990099',
      ]);

      return response()->json([
        'message' => 'Contacto!',
        'data' => $contact,
      ], 200);
    });
});
