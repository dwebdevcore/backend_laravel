<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BusinessPostTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        Passport::actingAs($user);

        $businessPost = factory(\App\Models\BusinessPost::class)->make();
        $params       = [
            'text'        => $businessPost->text,
            'business_id' => $businessPost->business->uuid,
            'expire_date' => $businessPost->expire_date->format("Y-m-d"),
            'photo'       => 'data:image/png;base64,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'
        ];

        $response = $this->json('POST', '/api/v1/business-posts', $params);
        $response
            ->assertStatus(201)
            ->assertJson([
                'business_id' => $businessPost->business_id
            ]);

        $this->assertDatabaseHas('business_posts', [
            'business_id' => $businessPost->business_id,
            'user_id'     => $user->id
        ]);

        $image = $response->json('images');
        Storage::disk('local')->assertExists(last($image)['path']);
    }

    public function testActiveIndex()
    {
        $user = factory(\App\Models\User::class)->make();

        Passport::actingAs($user);

        $activeBusinessPost = factory(\App\Models\BusinessPost::class)->create();
        $params             = [
            'business_id' => $activeBusinessPost->business->uuid
        ];

        $response = $this->json('GET', '/api/v1/active-business-posts', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);
    }

    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->make();

        Passport::actingAs($user);

        $activeBusinessPost = factory(\App\Models\BusinessPost::class)->create();
        $params             = [
            'business_id' => $activeBusinessPost->business->uuid
        ];

        $response = $this->json('GET', '/api/v1/business-posts', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'business_id',
                        'user_id',
                        'expire_date',
                        'text',
                        'meta'
                    ]
                ]
            ]);
    }

    public function testShow()
    {
        $user = factory(\App\Models\User::class)->make();

        Passport::actingAs($user);

        $activeBusinessPost = factory(\App\Models\BusinessPost::class)->create();
        $response           = $this->json('GET', "/api/v1/business-posts/{$activeBusinessPost->uuid}");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'business_id',
                'user_id',
                'expire_date',
                'text',
                'meta'
            ]);
    }
}
