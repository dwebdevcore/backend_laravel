<?php

namespace App\Nova;

use Acme\ImageBox\ImageBox;
use Acme\MapBox\MapBox;
use Acme\MapField\MapField;
use App\Nova\Filters\BusinessCategory;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Business extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Business';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * @var bool
     */
    protected static $searchable = true;

    /**
     * @var array
     */
    public static $with = ['attributes', 'totalEmailAttributes'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * @var array
     */
    public static $indexDefaultOrder = [
        'internal_score' => 'desc'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }
        return $query;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            MapField::make('location')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex(),
            ID::make('id')
                ->hideFromIndex()
                ->hideFromDetail(),
            Text::make('name')
                ->asHtml()
                ->displayUsing(function ($name) {
                    return view('partials.business-name', [
                        'name' => $name,
                        'id'   => $this->id,
                    ])->render();
                })
                ->sortable(),
            Text::make('uuid')
                ->onlyOnDetail(),
            Avatar::make('Cover', 'cover_path')
                ->disk('remote')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromDetail(),
            Text::make('#reviews', 'total_reviews')
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->hideFromDetail(),
            Text::make('#posts', 'total_posts')
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->hideFromDetail(),
            Text::make('#attributes', 'total_attributes')
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->hideFromDetail(),
            Text::make('#email', 'total_email_attributes')
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->hideFromDetail(),
            Text::make('lat')
                ->hideFromIndex()
                ->hideFromDetail(),
            Text::make('lng')
                ->hideFromIndex()
                ->hideFromDetail(),
            BelongsToMany::make('Categories', 'categories', Category::class),
            HasMany::make('DataAI Keywords', 'keywords', BusinessKeyword::class),
            new Panel('Posts', [
                ImageBox::make('postImages')
                    ->hideFromIndex()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),
            ]),
            HasMany::make('Reviews', 'reviews', BusinessReview::class),
            HasMany::make('Attributes', 'attributes', BusinessAttribute::class),
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
        return [
            new MapBox,
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new BusinessCategory,
        ];
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
}
