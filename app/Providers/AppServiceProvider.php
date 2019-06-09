<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use App\Repositories\Products\ProductsRepository;
use App\Repositories\Products\EloquentProductsRepository;
use App\Repositories\Products\ElasticsearchProductsRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductsRepository::class, function($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.

            if (!config('services.search.enabled')) {
                return new EloquentProductsRepository();
            }

            return new ElasticsearchProductsRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();


    }
    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
