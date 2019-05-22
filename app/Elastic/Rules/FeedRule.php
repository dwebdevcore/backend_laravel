<?php
/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 11/21/18
 * Time: 1:11 PM
 */

namespace App\Elastic\Rules;

class FeedRule
{
    /**
     * @param $lat
     * @param $lng
     * @return array
     */
    public static function build($lat, $lng)
    {
        $rule = [
            'body'  => [
                'query'    => [
                    'function_score' => [
                        'score_mode' => 'max',
                        'query'      => [
                            'bool' => [
                                'must' => [
                                    [
                                        'nested' => [
                                            'path'  => 'images',
                                            'query' => [
                                                'exists' => [
                                                    'field' => 'images'
                                                ]
                                            ]
                                        ]
                                    ],
                                ],
                                'should' => [
                                    BusinessRule::openedHours(null),
                                ]
                            ],
                        ],
                        'functions'  => [
                            [
                                'gauss' => [
                                    'location' => [
                                        'origin' => "{$lat}, {$lng}",
                                        'scale'  => '2km',
                                    ],
                                ]
                            ]
                        ]
                    ],
                ],
                'collapse' => [
                    'field'      => 'business_id',
                    'inner_hits' => [
                        'name' => 'top',
                        'size' => 5,
                        'sort' => [
                            '_script' => [
                                'script' => 'Math.random()',
                                'type'   => 'number',
                                'order'  => 'asc'
                            ],
                        ],
                    ]
                ],
            ]
        ];

        return $rule;
    }

    /**
     * @param $businessId
     * @return array
     */
    public static function business($businessId)
    {
        $rule = [
            'bool' => [
                'must' => [
                    [
                        'nested' => [
                            'path'  => 'images',
                            'query' => [
                                'exists' => [
                                    'field' => 'images'
                                ]
                            ]
                        ]
                    ],
                    [
                        'match' => [
                            'business_id' => $businessId
                        ]
                    ]
                ]
            ]
        ];

        return $rule;
    }
}