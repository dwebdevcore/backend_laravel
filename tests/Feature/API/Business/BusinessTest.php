<?php

namespace Tests\Feature\API\Business;

use App\Elastic\Rules\AggregationRule;
use App\Models\MapPreset;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;


class BusinessTest extends TestCase
{
    /**
     * Create business
     *
     * @return void
     */
    public function testStoreSuccess()
    {
        $user     = factory(\App\Models\User::class)->create();
        $business = factory(\App\Models\Business::class)->make();
        $params   = [
            'name' => $business->name,
            'lat'  => $business->lat,
            'lng'  => $business->lng
        ];

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/businesses', $params);

        $response
            ->assertStatus(201)
            ->assertJson([
                     'name'    => $business->name,
                     'lat'     => $business->lat,
                     'lng'     => $business->lng,
                     'user_id' => $user->id
                ]);
        $this->assertDatabaseHas('businesses', [
            'uuid' => $response->json('id')
        ]);
    }

    /**
     * Forbidden to create
     */
    public function testStoreForbidden() {
        $business = factory(\App\Models\Business::class)->make();
        $params   = [
            'name' => $business->name,
            'lat'  => $business->lat,
            'lng'  => $business->lng
        ];

        $response = $this->json('POST', '/api/v1/businesses', $params);
        $response
            ->assertStatus(401);
    }

    /**
     * Fetching business by ID
     */
    public function testShow() {
        $user     = factory(\App\Models\User::class)->make();
        $business = factory(\App\Models\Business::class)->create();

        Passport::actingAs($user);

        $response = $this->json('GET', "/api/v1/businesses/{$business->uuid}");
        $response
            ->assertStatus(200)
            ->assertJson([
                 'id'   => $business->uuid,
                 'name' => $business->name
            ]);
    }

    public function testGetStats() {
        $user = factory(\App\Models\User::class)->make();

        Passport::actingAs($user);

        $hosts = [
            env('SCOUT_ELASTIC_HOST', 'localhost:9000')
        ];

        $topLeft['lat']     = 52.71;
        $topLeft['lng']     = -2.27;
        $bottomRight['lat'] = 51.02;
        $bottomRight['lng'] = 3.79;

        $elasticClient = ClientBuilder::create()->setHosts($hosts)->build();
        $search        = $elasticClient->search(AggregationRule::buildRule($topLeft, $bottomRight));
        $search        = $search['aggregations'];
        $params        = [
            'top_left'     => $topLeft,
            'bottom_right' => $bottomRight
        ];

        $response = $this->json('GET', '/api/v1/businesses/stats', $params);

        $response
            ->assertStatus(200)
            ->assertJson([
                'totalBusinesses' => $search['total_businesses']['value'],
                'totalImages'     => $search['total_images']['value'],
                'totalReviews'    => $search['total_reviews']['value']
            ]);
    }

    public function testGeoJson() {
        $response = $this->json('GET', '/api/v1/businesses/geo-json');
        $response
            ->assertStatus(200);

        $fileToDownload = last(explode("/", config('filesystems.geojson_path')));
        Storage::disk('local')->assertExists($fileToDownload);
    }

    public function testBusinessWithInActiveMapPreset()
    {
        $mapPresetHour = factory(\App\Models\MapPresetHours::class)->create([
            'open_period_mins'  => date('H:i', strtotime('-30 minutes')),
            'close_period_mins' => date('H:i', strtotime('+30 minutes')),
            'repeat'            => 'weekly'
        ]);

        $mapPreset = MapPreset::find($mapPresetHour->id);
        $user      = factory(\App\Models\User::class)->make();
        $params    = [
            'map_preset_id' => $mapPreset->uuid
        ];

        Passport::actingAs($user);

        $response = $this->json('GET', "/api/v1/businesses", $params);
        $response
            ->assertStatus(500)
            ->assertJson([
                'message' => 'Map preset is inactive currently'
            ])
        ;
    }

    public function testBusinessWithActiveMapPreset()
    {
        $mapPresetHour = factory(\App\Models\MapPresetHours::class)->create([
            'wd_' . date('w')   => true,
            'open_period_mins'  => date('H:i', strtotime('-30 minutes')),
            'close_period_mins' => date('H:i', strtotime('+30 minutes')),
            'repeat'            => 'weekly'
        ]);

        $mapPreset = MapPreset::find($mapPresetHour->id);
        $user      = factory(\App\Models\User::class)->make();
        $params    = [
            'map_preset_id' => $mapPreset->uuid
        ];

        Passport::actingAs($user);

        $response = $this->json('GET', "/api/v1/businesses", $params);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);

    }
}
