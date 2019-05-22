<?php

namespace Tests\Feature;

use App\Models\BusinessReview;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BusinessReviewTest extends TestCase
{
    /**
     *
     */
    public function testStore()
    {
        $user = factory(\App\Models\User::class)->create();
        Passport::actingAs($user);

        $businessReview = factory(BusinessReview::class)->make();
        $params       = [
            'comment'     => $businessReview->comment,
            'business_id' => $businessReview->business->uuid,
            'code'        => $businessReview->code,
            'photo'       => 'iVBORw0KGgoAAAANSUhEUgAAADgAAAA4CAYAAACohjseAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJBJREFUeNrs2rERgzAQRUHJQwGUoBLspu2BZnAJlEAHMgqcOXP22Q0gfiMR3A21916S3Uo4gQIFChT4j+m1rtv5vof2vccJtuADbNP5eARH7gUAAIBf6nNZ5uSJfgy8W/LAO1YWR/ANPXykAAAA1zRWFi15ov+uLObUiX6sLPbgG7pX/4sKFChQoMALB34EGACdOBjKuiIUuAAAAABJRU5ErkJggg=='
        ];

        $response = $this->json('POST', '/api/v1/business-reviews', $params);
        $response
            ->assertStatus(201)
            ->assertJson([
                 'business_id' => $businessReview->business_id
            ])
        ;

        $this->assertDatabaseHas('business_reviews', [
            'business_id' => $businessReview->business_id,
            'user_id'     => $user->id
        ]);
    }
}
