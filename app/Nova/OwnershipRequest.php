<?php

namespace App\Nova;

use App\Nova\Actions\OwnershipRequestConfirmAction;
use App\Nova\Actions\OwnershipRequestAction;
use App\Nova\Actions\OwnershipRequestRejectAction;
use App\Nova\Filters\OwnershipRequestStatus;
use App\Nova\Filters\OwnershipRequestType;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class OwnershipRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\OwnershipRequest';

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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereNull('confirmed_at');
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
            Select::make('Method', 'method')->options([
                  'email'   => 'email',
                  'phone'   => 'phone',
                  'support' => 'support'
            ]),
            Boolean::make('Confirmed', 'confirmed_at'),
            BelongsTo::make('Business', 'business', Business::class)
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            BelongsTo::make('User', 'user', User::class)
                ->hideWhenCreating()
                ->hideWhenUpdating(),
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
        return [
            new OwnershipRequestType(),
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
        return [
            new OwnershipRequestConfirmAction(),
            new OwnershipRequestRejectAction()
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        if (null !== $this->confirmed_at) {
            return false;
        }

        if (in_array($request->get('action'), ['reject-request', 'confirm-request'])) {
            return true;
        }

        return false;
    }
}
