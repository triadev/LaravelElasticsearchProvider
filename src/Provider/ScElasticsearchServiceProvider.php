<?php
namespace Triadev\Es\Provider;

use Triadev\Es\Console\Commands\Index\Create as CreateIndex;
use Triadev\Es\Console\Commands\Index\Delete as DeleteIndex;
use Triadev\Es\Console\Commands\Alias\Create as CreateAlias;
use Triadev\Es\Console\Commands\Alias\Delete as DeleteAlias;
use Triadev\Es\Console\Commands\Migration\Activate;
use Triadev\Es\Console\Commands\Migration\Migrate;
use Triadev\Es\Console\Commands\Migration\Reindex;
use Triadev\Es\Console\Commands\Version\Overview as OverviewVersion;
use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Contract\ScElasticsearchClientContract;
use Triadev\Es\Contract\ScElasticsearchDocumentContract;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Contract\ScElasticsearchMappingContract;
use Triadev\Es\Contract\ScElasticsearchSearchContract;
use Triadev\Es\ScElasticsearchAlias;
use Triadev\Es\ScElasticsearchClient;
use Triadev\Es\ScElasticsearchDocument;
use Triadev\Es\ScElasticsearchIndex;
use Triadev\Es\ScElasticsearchMapping;
use Illuminate\Support\ServiceProvider;
use Triadev\Es\ScElasticsearchSearch;

/**
 * Class ScElasticsearchServiceProvider
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\Es\Provider
 */
class ScElasticsearchServiceProvider extends ServiceProvider
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
            __DIR__ . '/../Config/config.php' => config_path('sc-elasticsearch.php'),
        ], 'config');

        $this->mergeConfigFrom($source, 'sc-elasticsearch');

        // Console
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateIndex::class,
                DeleteIndex::class,
                CreateAlias::class,
                DeleteAlias::class,
                OverviewVersion::class,
                Migrate::class,
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
        $this->app->singleton(ScElasticsearchClientContract::class, function () {
            return new ScElasticsearchClient();
        });

        $this->app->singleton(ScElasticsearchIndexContract::class, function () {
            return new ScElasticsearchIndex(app(ScElasticsearchClientContract::class));
        });

        $this->app->singleton(ScElasticsearchAliasContract::class, function () {
            return new ScElasticsearchAlias(app(ScElasticsearchClientContract::class));
        });

        $this->app->singleton(ScElasticsearchDocumentContract::class, function () {
            return new ScElasticsearchDocument(app(ScElasticsearchClientContract::class));
        });

        $this->app->singleton(ScElasticsearchSearchContract::class, function () {
            return new ScElasticsearchSearch(app(ScElasticsearchClientContract::class));
        });

        $this->app->singleton(ScElasticsearchMappingContract::class, function () {
            return new ScElasticsearchMapping(
                app(ScElasticsearchClientContract::class),
                app(ScElasticsearchIndexContract::class)
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return [
            ScElasticsearchClientContract::class,
            ScElasticsearchIndexContract::class,
            ScElasticsearchAliasContract::class,
            ScElasticsearchMappingContract::class,
            ScElasticsearchDocumentContract::class,
            ScElasticsearchSearchContract::class
        ];
    }
}
