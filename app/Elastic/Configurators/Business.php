<?php

namespace App\Elastic\Configurators;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class Business extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'filter'    => [
                'synonym_filter' => [
                    'type'          => 'synonym',
                    'synonyms_path' => 'analysis/synonym.txt'
//                    'synonyms' => [
//                        "grooming, cleaning"
//                    ]
                ]
            ],
            'tokenizer' => [
                'comma' => [
                    'type'    => 'pattern',
                    'pattern' => ','
                ]
            ],
            'analyzer'  => [
                'english'             => [
                    'type'      => 'standard',
                    'stopwords' => '_english_'
                ],
                'synonym_analyzer'    => [
                    'tokenizer' => 'standard',
                    'filter'    => ['lowercase', 'synonym_filter']
                ],
                'whitespace_analyzer' => [
                    'type'      => 'custom',
                    'tokenizer' => 'comma',
                    'filter'    => [
                        'trim', 'lowercase'
                    ]
                ],
                'substring_analyzer' => [
                    'tokenizer' => 'keyword',
                    'filter'    => ['lowercase']
                ]
            ]
        ]
    ];
}