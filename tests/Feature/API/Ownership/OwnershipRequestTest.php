<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\BusinessAttribute;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OwnershipRequestTest extends TestCase
{
    public function testRequestOwnershipEmail()
    {
        $user     = factory(User::class)->create();
        $business = factory(Business::class)->create([
            'user_id' => $user->id,
        ]);
        $attributes = factory(BusinessAttribute::class)->create([
            'business_id' => $business->id,
            'key'         => 'email',
            'value'       => $user->email
        ]);

        Passport::actingAs($user);

        $params = [
            'method'   => 'email',
            'address'  => $user->email,
            'userInfo' => []
        ];

        $response = $this->json('POST', "/api/v1/ownership-requests/{$business->id}", $params);
        $response
            ->assertStatus(200)
            ->assertSeeText("true");

        $response = $this->json('GET', "/api/v1/ownership-requests/{$business->id}");
        $response
            ->assertStatus(200)
            ->assertJson([
                 'user_id'     => $user->id,
                 'business_id' => $business->id,
                 'method'      => 'email',
                 'address'     => $user->email
             ]);
    }

    public function testRequestOwnershipPhone() {
        $user     = factory(User::class)->create();
        $business = factory(Business::class)->create([
             'user_id' => $user->id,
         ]);
        $attributes = factory(BusinessAttribute::class)->create([
            'business_id' => $business->id,
            'key'         => 'phone',
            'value'       => $user->phone
        ]);

        Passport::actingAs($user);

        $params = [
            'method'   => 'phone',
            'address'  => $user->phone,
            'userInfo' => []
        ];

        $response = $this->json('POST', "/api/v1/ownership-requests/{$business->id}", $params);
        $response
            ->assertStatus(200)
            ->assertSeeText("true");

        $response = $this->json('GET', "/api/v1/ownership-requests/{$business->id}");
        $response
            ->assertStatus(200)
            ->assertJson([
                 'user_id'     => $user->id,
                 'business_id' => $business->id,
                 'method'      => 'phone',
             ]);
    }

    public function testGetMethods() {
        $user     = factory(User::class)->create();
        $business = factory(Business::class)->create([
             'user_id' => $user->id,
         ]);
        $attributes = factory(BusinessAttribute::class)->create([
            'business_id' => $business->id,
            'key'         => 'phone',
            'value'       => $user->phone
        ]);

        Passport::actingAs($user);

        $response = $this->json('GET', "/api/v1/ownership-methods/{$business->id}");
        $response
            ->assertStatus(200)
            ->assertJson([
                 [
                     'method' => 'phone'
                 ]
             ]);
    }
}
