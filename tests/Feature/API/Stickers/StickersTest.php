<?php

namespace Tests\Feature\Stickers;

use Laravel\Passport\Passport;
use Tests\TestCase;

class StickersTest extends TestCase
{
    /**
     * Test fetch all stickers
     *
     * @return void
     */
    public function testIndex()
    {
        $user     = factory(\App\Models\User::class)->make();
        $stickers = factory(\App\Models\Sticker::class)->create();

        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/stickers');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'image',
                        'tags'
                    ]
                ]
            ])
        ;
    }

    /**
     * Test get stickers in a category
     */
    public function testSearchByCategory() {
        $user     = factory(\App\Models\User::class)->make();
        $category = factory(\App\Models\StickerCategory::class)->create();
        $sticker  = factory(\App\Models\Sticker::class)->create();
        $sticker->categories()->attach($category);

        Passport::actingAs($user);

        $params = [
            'category_id' => $category->id
        ];

        $response = $this->json('GET', '/api/v1/stickers', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                  'data' => [
                      [
                          'id',
                          'image',
                          'tags'
                      ]
                  ]
            ])
            ->assertJson([
                 'data' => [
                     [
                         'id' => $sticker->id
                     ]
                 ]
            ])
        ;
    }
}
