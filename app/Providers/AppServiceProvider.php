<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\BusinessAttribute;
use App\Models\BusinessCategory;
use App\Models\BusinessReview;
use App\Image\FaceDetection\GoogleFaceDetectionService;
use App\Observers\BusinessAttributeObserver;
use App\Observers\BusinessCategoryObserver;
use App\Observers\BusinessObserver;
use App\Observers\BusinessReviewObserver;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();

        BusinessAttribute::observe(BusinessAttributeObserver::class);
        BusinessCategory::observe(BusinessCategoryObserver::class);
        BusinessReview::observe(BusinessReviewObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GoogleFaceDetectionService::class, function() {
            return new GoogleFaceDetectionService(new ImageAnnotatorClient());
        });
    }
}
