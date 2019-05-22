<?php

namespace App\Elastic\Rules;

use ScoutElastic\SearchRule;

class BusinessSearchRule extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'plain'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        $query = $this->builder->query;
        if (preg_match("/\"(.*)\"/", $query, $matches)) {
            return [
                'must' => [
                    'wildcard' => [
                        'exact_name' => [
                            'value' => trim("*{$matches[1]}*")
                        ]
                    ]
                ]
            ];
        }

        return [
            'should' => [
                [
                    'multi_match' => [
                        'query'     => $query,
                        'fuzziness' => 5
                    ]
                ],
                [
                    'nested' => [
                        'path' => 'categories',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    'match' => [
                                        'categories.name' => $query
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
