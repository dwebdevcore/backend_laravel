<?php

namespace App\Elastic\Rules;

class AttributesCountRule {
    public static function buildRule(array $topLeft, array $bottomRight) {
        return [
            'index' => 'business_attribute',
            'type'  => 'business_attributes',
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
                    'keys' => [
                        'terms' => [
                            'field' => 'key'
                        ]
                    ]
                ]
            ]
        ];
    }
}