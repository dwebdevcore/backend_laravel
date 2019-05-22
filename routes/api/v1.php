<?php

/*
|--------------------------------------------------------------------------
| V1 API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for V1.
|
*/

Route::get('/manifest', function () {
    return [
        'servers' => [
            [
                'label' => 'v0.1',
                'url' => 'http://104.248.44.122/api/v1'
            ],
            [
                'label' => 'v0.2',
                'url' => 'http://167.99.193.195/api/v1'
            ],
            [
                'label' => 'Internal Testing 1',
                'url' => 'http://104.248.253.106/api/v1'
            ],
            [
                'label' => 'Internal Testing 2',
                'url' => 'http://167.99.93.110/api/v1'
            ]
        ]
    ];
});

Route::post('/login', 'LoginController@store');
Route::post('/register', 'RegisterController@store');

/**
 *  Business
 */
Route::get('/businesses/geo-json', 'BusinessesController@geoJson');
Route::get('/businesses/stats', 'BusinessesController@stats');
Route::post('/business-cover', 'BusinessCoverController@store');


Route::group(['middleware' => ['auth:api']], function () {
    Route::delete('/login', 'LoginController@destroy');

    /**
     * User
     */
    Route::patch('/users/{id}', 'UsersController@update');

    /**
     * User categories
     */
    Route::get('/user-categories', 'UserCategoriesController@index');
    Route::post('/user-categories', 'UserCategoriesController@store');

    /**
     * User businesses
     */
    Route::post('/user-businesses', 'UserBusinessesController@store');
    Route::delete('/user-businesses', 'UserBusinessesController@delete');

    /**
     * Business
     */
    Route::get('/businesses', 'BusinessesController@index');
    Route::get('/businesses/{id}', 'BusinessesController@show');
    Route::get('/business-search', 'BusinessSearchController@index');
    Route::post('/businesses', 'BusinessesController@store');

    /**
     * Business Posts
     */
    Route::post('/business-posts', 'BusinessPostsController@store');
    Route::get('/business-posts', 'BusinessPostsController@index');
    Route::get('/business-posts/{id}', 'BusinessPostsController@show');
    Route::get('/active-business-posts', 'ActiveBusinessPostsController@index');

    /**
     * Business Reviews
     */
    Route::post('/business-reviews', 'BusinessReviewsController@store');

    /**
     * Business Feed
     */
    Route::get('/business-feed/{businessId}', 'BusinessFeedController@index');

    /**
     * User feed
     */
    Route::get('/user-feed', 'UserFeedController@index');

    /**
     * Images
     */
    Route::any('/face-detection', 'FaceDetectionController@index');

    /**
     * Explore
     */
    Route::get('/explore', 'ExploreController@index');

    /**
     *  Discover
     */
    Route::get('/discover', 'DiscoverController@index');

    /**
     *  Map Presets
     */
    Route::get('/map-presets', 'MapPresetsController@index');

    /**
     * Stickers
     */
    Route::get('/sticker-categories', 'StickerCategoriesController@index');
    Route::get('/stickers', 'StickersController@index');

    /**
     * Ownership
     */
    Route::get('/ownership-methods/{businessId}', 'Ownership\MethodsController@index');
    Route::post('/ownership-requests/{businessId}', 'Ownership\RequestsController@store');
    Route::get('/ownership-requests/{businessId}', 'Ownership\RequestsController@index');
    Route::post('/confirm-ownership/{businessId}', 'Ownership\ConfirmController@index');

    /**
     * Feed
     */

    Route::get('/feed', 'FeedController@index');

    /**
     * Logged in user data
     */
    Route::get('/user', function (Illuminate\Http\Request $request) {
        return $request->user();
    });
});
