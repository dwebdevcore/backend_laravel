<?php

namespace Tests\Feature\Stickers;

use Laravel\Passport\Passport;
use Tests\TestCase;

class StickerCategoriesTest extends TestCase
{
    /**
     * Test to get sticker categories
     *
     * @return void
     */
    public function testIndex()
    {
        $user     = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\StickerCategory::class)->create();

        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/sticker-categories');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name'
                    ]
                ]
        ]);
    }
}
