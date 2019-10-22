<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    protected $loggedInUser;

    protected $user;

    protected $headers;

    protected $token;

    public function setUp()
    {
        parent::setUp();

        $this->prepareForTests();

    }

    /**
     * Migrar la base de datos.
     * Esto mejora el rendimiento de la prueba.
     *
     */
    public function prepareForTests()
    {

        $users = factory(\App\User::class)->times(2)->create();

        $this->loggedInUser = auth()->login($users[0]);

        $this->token = auth()->tokenById(auth()->user()->id);

        $this->user = $users[1];

        $this->headers = [
            'Authorization' => "Bearer {$this->token}"
        ];


    }
}
