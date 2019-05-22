<?php

namespace Tests\Feature;

use Tests\TestCase;

class BusinessCoverTest extends TestCase
{
    public function testSetCover()
    {
        $businessImage = factory(\App\Models\BusinessImage::class)->create();
        $params = [
            'id'          => $businessImage->id,
            'business_id' => $businessImage->business_id
        ];

        $response = $this->json('POST', '/api/v1/business-cover', $params);
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'ok'
            ]);

        $this->assertDatabaseHas('business_images', [
            'id'    => $businessImage->id,
            'cover' => true
        ]);
    }
}
