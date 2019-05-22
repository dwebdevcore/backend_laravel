<?php

namespace App\Nova;

use Acme\ThumbField\ThumbField;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;

class BusinessAttribute extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\BusinessAttribute';

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
     * @var array
     */
    public static $with = ['label'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            new Panel('Attributes', $this->businessAttributesFields()),
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

    /**
     * @return array
     */
    protected function businessAttributesFields()
    {
        return [
            Text::make('Key', 'key')
                ->hideFromIndex()
                ->hideFromDetail(),
            Text::make('Key', 'label.value')->withMeta(['value' => ($this->label['value'] !== null) ? $this->label['value'] : $this->key])
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            preg_match("/(https?:\/\/.*\.(?:png|jpg))/i", $this->value) ? (ThumbField::make('Image', 'value')) : (Text::make('Value', 'value'))
        ];
    }

    /**
     * @param Request $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return false;
    }
}
