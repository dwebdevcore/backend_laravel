<?php

namespace Tests\Feature;

use App\Models\Business;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DiscoverTest extends TestCase
{
    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->create();
        Passport::actingAs($user);

        $business = factory(Business::class)->create();
        $params   = [
            'business_id' => $business->uuid
        ];

        $response = $this->json('GET', '/api/v1/discover', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);
    }
}
