<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class BusinessReview extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\BusinessReview';

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

    public static $with = ['keywords'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('id')
                ->hideFromIndex()
                ->hideFromDetail(),
            Text::make('comment')
                ->asHtml()
                ->hideFromDetail()
                ->hideWhenUpdating()
                ->hideWhenCreating()
                ->displayUsing(function ($review) {
                    return view('partials.business-review-keywords', [
                        'review'   => $review,
                        'keywords' => $this->keywords_list
                    ])->render();
                })
            ,
            Text::make('comment')->hideFromIndex(),
            Number::make('code')->displayUsing(function ($code) {
                return round($code / 5 * 100) . '%';
            }),
            DateTime::make('Date', 'created_at')->format('DD/MM/YYYY')
                ->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            HasMany::make('Images', 'images', BusinessReviewImage::class),
            HasMany::make('Keywords', 'keywords', BusinessReviewKeyword::class),

        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
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
     * @param Request $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return false;
    }
}
