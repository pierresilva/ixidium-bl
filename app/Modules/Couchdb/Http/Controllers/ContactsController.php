<?php

namespace App\Modules\Couchdb\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOnCouch\CouchClient;

class ContactsController extends Controller
{
    protected $client;
    protected $selector;

    protected $collection = 'contacts_collection';

    public function __construct()
    {
        $this->client = new CouchClient('http://localhost:5984/', 'contacts');
        $this->selector = [
            '$and' =>
            [
                [
                    'deleted_at' => [
                        '$eq' => null,
                    ],
                ],
                [
                    'type' => [
                        '$eq' => $this->collection,
                    ],
                ],
            ],
        ];
    }

    /**
     * Visualiza el listado del recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        $perPage = $request->get('perpage') ? $request->get('perpage') : 10;
        $skip = $request->get('page') > 1 ? $perPage * ($request->get('page') - 1) : 0;
        $limit = $perPage;        

        $docs = $this->client->skip($skip)->limit($limit)->find($this->selector);

        return response()->json([
            'data' => $docs,
            'meta' => [
                'total' => count($docs),
            ],
        ], 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // $newContact = $request->all();

        $newContact = new \stdClass();
        $newContact->first_name = $request->get('first_name');
        $newContact->last_name = $request->get('last_name');
        // tomestamps
        $newContact->created_at = \Carbon\Carbon::now()->toDateTimeString();
        $newContact->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        // softdeletes
        $newContact->deleted_at = null;
        // add always type
        $newContact->type = $this->collection;

        try {
            $response = $this->client->storeDoc($newContact);
            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $doc = $this->client->getDoc($id);
        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                return response()->json([
                    'message' => 'El contacto con la _id ' . $id . ' no existe!',
                ], 404);
            }
        }

        return response()->json([
            'message' => 'Se obtubo el item con éxito!',
            'data' => $doc,
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        // get the document
        try {
            $doc = $this->client->getDoc($id);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }

        // make changes
        $doc->first_name = $request->get('first_name');
        $doc->last_name = $request->get('last_name');
        $doc->updated_at = \Carbon\Carbon::now()->toDateTimeString();
        // update the document on CouchDB server
        try {
            $response = $this->client->storeDoc($doc);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
        return response()->json([
            'message' => 'Item actualizado con éxito!',
            'data' => $response,
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // get the document
        try {
            $doc = $this->client->getDoc($id);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }

        // make changes
        $doc->deleted_at = \Carbon\Carbon::now()->toDateTimeString();
        // update the document on CouchDB server
        try {
            $response = $this->client->storeDoc($doc);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
        return response()->json([
            'message' => 'Item eliminado con éxito!',
            'data' => $response,
        ], 202);
    }
}
