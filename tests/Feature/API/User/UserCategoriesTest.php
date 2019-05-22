<?php

namespace Tests\Feature\API\User;

use App\Models\Category;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserCategoriesTest extends TestCase
{

    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $user     = factory(User::class)->create();
        $user->categories()->attach($category);

        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/user-categories');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'name'
                ]
            ]);
    }

    public function testStore()
    {
        $category = factory(Category::class)->create();
        $user     = factory(User::class)->create();

        Passport::actingAs($user);

        $params = [
            'user_id'     => $user->uuid,
            'category_id' => $category->uuid
        ];

        $response = $this->json('POST', '/api/v1/user-categories', $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'uuid',
                'name'
            ]);
    }
}
