<?php

namespace Tests\Unit\Business;

use App\Models\Business;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    /**
     * Test 0 internal score
     *
     * @return void
     */
    public function testZeroInternalScore()
    {
        $business = factory(Business::class)->create();
        $business = Business::find($business->id);

        $this->assertEquals(0, $business->internal_score);
    }

    /**
     * Test business with review only
     * Score must be 20
     */
    public function testReviewInternalScore() {
        $business       = factory(Business::class)->create();
        $businessReview = factory(\App\Models\BusinessReview::class)->create([
             'business_id' => $business->id
        ]);

        $business = Business::find($business->id);
        $this->assertEquals(20, $business->internal_score);
    }

    /**
     * Test business with categories only
     * Score must be 20
     */
    public function testCategoriesInternalScore() {
        $business         = factory(Business::class)->create();
        $businessCategory = factory(\App\Models\BusinessCategory::class)->create([
            'business_id' => $business->id
        ]);

        $business = Business::find($business->id);
        $this->assertEquals(20, $business->internal_score);
    }

    /**
     * Test business with addy attribute only
     * Score must be 20
     */
    public function testAddyAttributesInternalScore() {
        $business          = factory(Business::class)->create();
        $businessAttribute = factory(\App\Models\BusinessAttribute::class)->create([
             'business_id' => $business->id,
             'key'         => 'addy'
        ]);

        $business = Business::find($business->id);
        $this->assertEquals(20, $business->internal_score);
    }

    /**
     * Test business with attributes count > 2
     * Score must be 20
     */
    public function testAttributesInternalScore() {
        $business         = factory(Business::class)->create();
        $businessCategory = factory(\App\Models\BusinessAttribute::class, 2)->create([
            'business_id' => $business->id,
        ]);

        $business = Business::find($business->id);
        $this->assertEquals(20, $business->internal_score);
    }

    /**
     * Score must be 100
     */
    public function testInternalScore() {
        $business = factory(Business::class)->create();
        factory(\App\Models\BusinessReview::class)->create([
            'business_id' => $business->id
        ]);
        factory(\App\Models\BusinessPost::class)->create([
            'business_id' => $business->id
        ]);
        factory(\App\Models\BusinessCategory::class)->create([
            'business_id' => $business->id
        ]);
        factory(\App\Models\BusinessAttribute::class)->create([
            'business_id' => $business->id,
            'key'         => 'addy'
        ]);
        factory(\App\Models\BusinessAttribute::class, 2)->create([
            'business_id' => $business->id,
        ]);

        $business = Business::find($business->id);
        $this->assertEquals(100, $business->internal_score);
    }

    /**
     * Test business basic score
     * Must be 80 - minimum
     */
    public function testScore() {
        $business = factory(Business::class)->create();
        $business = Business::find($business->id);

        $this->assertEquals(80, $business->score);
    }

    /**
     * Score must be 100
     */
    public function testGraterScore() {
        $business       = factory(Business::class)->create();
        $businessReview = factory(\App\Models\BusinessReview::class, 7)->create([
            'business_id' => $business->id,
            'code'        => 5
        ]);

        $business = Business::find($business->id);
        $this->assertEquals(100, $business->score);
    }
}
