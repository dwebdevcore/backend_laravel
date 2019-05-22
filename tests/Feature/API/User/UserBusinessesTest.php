<?php

namespace Tests\Feature\API\User;

use App\Models\Business;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserBusinessesTest extends TestCase
{
    public function testStore()
    {
        $user     = factory(User::class)->create();
        $business = factory(Business::class)->create();

        Passport::actingAs($user);

        $params = [
            'user_id'     => $user->uuid,
            'business_id' => $business->uuid
        ];

        $response = $this->json('POST', '/api/v1/user-businesses', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name'
            ]);

        $this->assertDatabaseHas('business_user', [
            'user_id' => $user->id,
            'business_id' => $business->id
        ]);
    }

    public function testDelete()
    {
        $user     = factory(User::class)->create();
        $business = factory(Business::class)->create();
        $user->businesses()->attach($business);

        Passport::actingAs($user);

        $params = [
            'user_id'     => $user->uuid,
            'business_id' => $business->uuid
        ];

        $response = $this->json('DELETE', '/api/v1/user-businesses', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name'
            ]);

        $this->assertDatabaseMissing('business_user', [
            'user_id' => $user->id,
            'business_id' => $business->id
        ]);
    }
}
