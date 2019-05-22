<?php

namespace App\Elastic\Configurators;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class BusinessAttribute extends IndexConfigurator
{
    use Migratable;

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
}