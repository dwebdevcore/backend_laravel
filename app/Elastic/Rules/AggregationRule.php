<?php
/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 10/15/18
 * Time: 8:26 PM
 */

namespace App\Elastic\Rules;

class AggregationRule {
    public static function buildRule(array $topLeft, array $bottomRight) {
        return [
            'index' => 'business',
            'type'  => 'businesses',
            'body'  => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'geo_bounding_box' => [
                                'location' => [
                                    'top_left' => [
                                        'lat' => $topLeft['lat'],
                                        'lon' => $topLeft['lng']
                                    ],
                                    'bottom_right' => [
                                        'lat' => $bottomRight['lat'],
                                        'lon' => $bottomRight['lng']
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
                'aggs' => [
                    'total_businesses' => [
                        'value_count' => [
                            'field' => 'id'
                        ]
                    ],
                    'total_images' => [
                        'sum' => [
                            'field' => 'total_images'
                        ]
                    ],
                    'total_reviews' => [
                        'sum' => [
                            'field' => 'total_reviews'
                        ]
                    ]
                ]
            ]
        ];
    }
}