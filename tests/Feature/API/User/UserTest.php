<?php

namespace Tests\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdate()
    {
        $user   = factory(\App\Models\User::class)->create();
        Passport::actingAs($user);

        $params = [
            'gender'    => 'male',
            'age_group' => '1'
        ];

        $response = $this->json('PATCH', "/api/v1/users/{$user->uuid}", $params);
        $response
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'uuid'      => $user->uuid,
            'gender'    => 'male',
            'age_group' => '1'
        ]);
    }
}
