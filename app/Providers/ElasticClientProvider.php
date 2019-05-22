<?php

namespace App\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticClientProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function () {
            $hosts = [
                env('SCOUT_ELASTIC_HOST', 'localhost:9000')
            ];

            return ClientBuilder::create()->setHosts($hosts)->build();
        });
    }

    public function provides()
    {
        return [ClientBuilder::class];
    }
}
