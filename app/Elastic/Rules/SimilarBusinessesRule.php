<?php

namespace App\Elastic\Rules;

use App\Models\Business;

class SimilarBusinessesRule
{
    /**
     * @param Business $business
     * @return array
     */
    public static function build(Business $business): array
    {
        return [
            'function_score' => [
                'score_mode' => 'max',
                'query'      => [
                    'bool' => [
                        'should' => [
                            [
                                'nested' => [
                                    'path'  => 'categories',
                                    'query' => [
                                        'bool' => [
                                            'should' => [
                                                'terms' => [
                                                    'categories.name' => $business->categories->pluck('name')->toArray(),
                                                ],
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'functions'  => [
                    [
                        'gauss' => [
                            'location' => [
                                'origin' => "{$business->lat}, {$business->lng}",
                                'scale'  => '2km',
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}