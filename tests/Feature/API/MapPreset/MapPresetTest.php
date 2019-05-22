<?php

namespace Tests\Feature;

use Laravel\Passport\Passport;
use Tests\TestCase;

class MapPresetTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(\App\Models\User::class)->make();
        Passport::actingAs($user);

        factory(\App\Models\MapPresetHours::class)->create([
            'wd_' . date('w')   => true,
            'open_period_mins'  => date('H:i', strtotime('-30 minutes')),
            'close_period_mins' => date('H:i', strtotime('+30 minutes')),
            'repeat'            => 'weekly'
        ]);
        $response = $this->json('GET', '/api/v1/map-presets');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'hours',
                        'categories'
                    ]
                ]
        ]);
    }
}
