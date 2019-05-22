<?php

namespace App\Elastic\Configurators;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class BusinessReview extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'substring_analyzer' => [
                    'tokenizer' => 'keyword',
                    'filter'    => ['lowercase']
                ]
            ]
        ]
    ];

    protected $defaultMapping = [
        'properties' => [
            'id'          => [
                'type' => 'keyword'
            ],
            'business_id' => [
                'type' => 'keyword'
            ],
            'user_id'     => [
                'type' => 'long'
            ],
            'type'        => [
                'type' => 'keyword'
            ],
            'location'    => [
                'type'  => 'geo_point',
                'index' => 'true'
            ],
            'images'      => [
                'type'       => 'nested',
                'properties' => [
                    'path' => [
                        'type' => 'text'
                    ]
                ]
            ],
            'code'        => [
                'type' => 'byte'
            ],
            'comment'     => [
                'type' => 'text'
            ],
            'meta'        => [
                'type' => 'text'
            ],
            'hours'          => [
                'type'       => 'nested',
                'properties' => [
                    'day_of_week'       => [
                        'type'  => 'byte',
                        'index' => 'true'
                    ],
                    'open_period_mins'  => [
                        'type'  => 'short',
                        'index' => 'true'
                    ],
                    'close_period_mins' => [
                        'type'  => 'short',
                        'index' => 'true'
                    ]
                ]
            ],
        ]
    ];
}
