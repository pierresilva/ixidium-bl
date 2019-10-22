<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuditActivities extends TestCase
{
    public function testIndex ()
    {
        $response = $this
            ->json('GET', 'api/audit/activities', []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Se obtuvo la colección con éxito!',
            ]);

        $response = $this
            ->json('GET', 'api/audit/activities', [
                'search' => 'hello'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Se obtuvo la colección con éxito!',
            ]);

    }
}
