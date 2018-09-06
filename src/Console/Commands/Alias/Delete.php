<?php
namespace Triadev\Es\Console\Commands\Alias;

use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Illuminate\Console\Command;
use Log;

class Delete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:es:alias:delete
                            {index : Index}
                            {alias : Alias}
                            {version : Version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an alias.';

    /**
     * Execute the console command.
     *
     * @param ElasticsearchAliasContract $elasticsearchAlias
     */
    public function handle(ElasticsearchAliasContract $elasticsearchAlias)
    {
        $index = $this->argument('index');
        $alias = $this->argument('alias');
        $version = $this->argument('version');

        Log::info(sprintf(
            "The alias is deleted: %s | %s | %s",
            $index,
            $alias,
            $version
        ));

        try {
            $elasticsearchAlias->deleteAlias($index, $alias, $version);

            Log::info(sprintf(
                "The alias was deleted: %s | %s | %s",
                $index,
                $alias,
                $version
            ));
        } catch (AliasNotFoundException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error(sprintf(
                "The alias was not deleted: %s | %s | %s | %s",
                $index,
                $alias,
                $version,
                $e->getMessage()
            ));
        }
    }
}
