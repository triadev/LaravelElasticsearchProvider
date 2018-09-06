<?php
namespace Triadev\Es\Provider;

use Triadev\Es\Console\Commands\Index\Create as CreateIndex;
use Triadev\Es\Console\Commands\Index\Delete as DeleteIndex;
use Triadev\Es\Console\Commands\Alias\Create as CreateAlias;
use Triadev\Es\Console\Commands\Alias\Delete as DeleteAlias;
use Triadev\Es\Console\Commands\Migration\Activate;
use Triadev\Es\Console\Commands\Migration\Deploy;
use Triadev\Es\Console\Commands\Migration\Migrate;
use Triadev\Es\Console\Commands\Migration\Reindex;
use Triadev\Es\Console\Commands\Version\Overview as OverviewVersion;
use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchClientContract;
use Triadev\Es\Contract\ElasticsearchDocumentContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Contract\ElasticsearchMappingContract;
use Triadev\Es\Contract\ElasticsearchSearchContract;
use Triadev\Es\ElasticsearchAlias;
use Triadev\Es\ElasticsearchClient;
use Triadev\Es\ElasticsearchDocument;
use Triadev\Es\ElasticsearchIndex;
use Triadev\Es\ElasticsearchMapping;
use Illuminate\Support\ServiceProvider;
use Triadev\Es\ElasticsearchSearch;
use Triadev\PrometheusExporter\Provider\PrometheusExporterServiceProvider;

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
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateIndex::class,
                DeleteIndex::class,
                CreateAlias::class,
                DeleteAlias::class,
                OverviewVersion::class,
                Migrate::class,
                Deploy::class,
                Reindex::class,
                Activate::class
            ]);
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ElasticsearchClientContract::class, function () {
            return app()->make(ElasticsearchClient::class);
        });
    
        $this->app->singleton(ElasticsearchIndexContract::class, function () {
            return app()->make(ElasticsearchIndex::class);
        });
    
        $this->app->singleton(ElasticsearchAliasContract::class, function () {
            return app()->make(ElasticsearchAlias::class);
        });
    
        $this->app->singleton(ElasticsearchDocumentContract::class, function () {
            return app()->make(ElasticsearchDocument::class);
        });
    
        $this->app->singleton(ElasticsearchSearchContract::class, function () {
            return app()->make(ElasticsearchSearch::class);
        });
    
        $this->app->singleton(ElasticsearchMappingContract::class, function () {
            return app()->make(ElasticsearchMapping::class);
        });
    }
}
