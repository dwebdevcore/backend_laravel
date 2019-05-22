<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class MapPresetBusinessHours extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\MapPresetBusinessHours';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Start time', 'open_period_mins')
                ->withMeta(['placeholder' => '8:30AM'])
                ->rules('regex:/(((0[1-9])|(1[0-2])):([0-5])(0|5)(A|P)M)/i')
                ->rules('required')
                ->hideFromDetail()
                ->hideFromIndex()
                ->withMeta(['value' => $this->open])
            ,
            Text::make('End time', 'close_period_mins')
                ->withMeta(['placeholder' => '6:30PM'])
                ->rules('regex:/(((0[1-9])|(1[0-2])):([0-5])(0|5)(A|P)M)/i')
                ->rules('required')
                ->hideFromDetail()
                ->hideFromIndex()
                ->withMeta(['value' => $this->close])
            ,
            Text::make('Start time', 'open')
                ->hideWhenCreating()
                ->hideWhenUpdating()
            ,
            Text::make('End time', 'close')
                ->hideWhenCreating()
                ->hideWhenUpdating()
            ,
            Boolean::make('Sunday', 'wd_0'),
            Boolean::make('Monday', 'wd_1'),
            Boolean::make('Tuesday', 'wd_2'),
            Boolean::make('Wednesday', 'wd_3'),
            Boolean::make('Thursday', 'wd_4'),
            Boolean::make('Friday', 'wd_5'),
            Boolean::make('Saturday', 'wd_6')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static function availableForNavigation(Request $request)
    {
        return false;
    }
}
