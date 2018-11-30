<?php
namespace Triadev\Es\Provider;

use Triadev\Es\Contract\ElasticsearchContract;
use Triadev\Es\Elasticsearch;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../Config/config.php');

        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('triadev-elasticsearch.php'),
        ], 'config');

        $this->mergeConfigFrom($source, 'triadev-elasticsearch');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ElasticsearchContract::class, function () {
            return app()->make(Elasticsearch::class);
        });
    }
}
