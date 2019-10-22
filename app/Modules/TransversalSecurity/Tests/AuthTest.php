<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * AdmiSome Store Test
     */
    public function testTransvarsalSecurityAuth()
    {
        $response = $this->json('POST', 'api/transversal-security/login', [
            'usuario' => 'some@email.com',
            'password' => 'xxxxx',
        ]);
        $response
            ->assertStatus(403)
            ->assertJson([
                'message' => 'Credenciales no validas!',
            ]);


        $response = $this->json('POST', 'api/transversal-security/login', [
            'usuario' => 'prueba.renova',
            'password' => '123456',
        ]);
        $response
            ->assertStatus(200);
        
    }
}