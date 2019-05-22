<?php

namespace App\Elastic\Rules;

use App\Models\Business;

class BusinessRule
{
    /**
     * @param $lat
     * @param $lng
     * @param string $query
     * @param null $categoryIds
     * @param $mapPreset
     * @return array
     */
    public static function build($lat, $lng, $query = '*', $categoryIds = null, $mapPreset = false)
    {
        $rule = [
            'function_score' => [
                'score_mode' => 'max',
                'query'      => [
                    'bool' => []
                ],
                'functions'  => []
            ]
        ];

        if ($mapPreset) {
            $rule['function_score']['query']['bool']['must'][] = self::openedHours($mapPreset);
        } else {
            $rule['function_score']['query']['bool']['should'][] = self::openedHours($mapPreset);
        }

        if ('*' !== $query) {
            $rule['function_score']['query']['bool']['should'][] = self::queryName($query);
        } else {
            $rule['function_score']['query']['bool']['should'][] = self::queryMatchAll();
        }

        if (null !== $lat && null !== $lng) {
            $rule['function_score']['functions'][] = [
                'gauss' => [
                    'location' => [
                        'origin' => "{$lat}, {$lng}",
                        'scale'  => '2km',
                    ],
                ]
            ];
        }

        if (null !== $categoryIds || $mapPreset) {
            $categoryIds = is_object($mapPreset) ? $mapPreset->categories->pluck('id')->toArray() : [$categoryIds];
            $rule['function_score']['query']['bool']['must'][] = [
                'nested' => [
                    'path'  => 'categories',
                    'query' => [
                        'bool' => [
                            'should' => [
                                'terms' => [
                                    'categories.pivot.category_id' => $categoryIds,
                                ],
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $rule;
    }

    /**
     * @param string $query
     * @return array
     */
    private static function queryName(string $query): array
    {
        return [
            'multi_match' => [
                'query'     => $query,
                'fuzziness' => Business::fuzziness,
                'boost'     => Business::boostNameMatch
            ]
        ];
    }

    /**
     * @return array
     */
    private static function queryMatchAll()
    {
        return [
            'match_all' => new \stdClass()
        ];
    }

    /**
     * @param $mapPreset
     * @return array
     */
    public static function openedHours($mapPreset)
    {
        $query = [
            'nested' => [
                'boost' => Business::boostOpened,
                'path'  => 'hours',
                'query' => [
                    'bool' => [

                    ]
                ]
            ]
        ];

        if ($mapPreset && !empty($mapPreset->businessHours->toArray())) {
            foreach ($mapPreset->businessHours as $businessHour) {
                $hours = [];
                for ($i = 0; $i < 7; $i++) {
                    if ($businessHour->{"wd_{$i}"}) {
                        $hours[] = $i;
                    }
                }
                $query['nested']['query']['bool']['must'][] = [
                    [
                        'terms' => [
                            'hours.day_of_week' => $hours
                        ]
                    ],
                    [
                        'range' => [
                            'hours.open_period_mins' => [
                                'lte' => $businessHour->open_period_mins
                            ]
                        ]
                    ],
                    [
                        'range' => [
                            'hours.close_period_mins' => [
                                'gte' => $businessHour->close_period_mins
                            ]
                        ]
                    ]
                ];
            }
        } else {
            $query['nested']['query']['bool']['must'] = [
                [
                    'match' => [
                        'hours.day_of_week' => date('w'),
                    ]
                ],
                [
                    'range' => [
                        'hours.open_period_mins' => [
                            'lte' => Business::currentMinutes()
                        ]
                    ]
                ],
                [
                    'range' => [
                        'hours.close_period_mins' => [
                            'gte' => Business::currentMinutes()
                        ]
                    ]
                ]
            ];
        }

        return [$query];
    }
}