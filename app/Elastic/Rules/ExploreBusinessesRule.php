<?php
namespace App\Elastic\Rules;

class ExploreBusinessesRule {
    /**
     * @param $lat
     * @param $lng
     * @param int $distance
     * @return array
     */
    public static function build($lat, $lng, $distance) {
        return [
            'should' => [
                [
                    'geo_distance' => [
                        'distance' => "{$distance}km",
                        'location' => [
                            'lat' => $lat,
                            'lon' => $lng
                        ]
                    ]
                ]
            ]
        ];
    }
}
