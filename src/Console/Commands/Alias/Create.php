<?php
namespace Triadev\Es\Console\Commands\Alias;

use Triadev\Es\Contract\ElasticsearchAliasContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:es:alias:create
                            {index : Index}
                            {alias : Alias}
                            {version : Version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an alias.';

    /**
     * Execute the console command.
     *
     * @param ElasticsearchAliasContract $elasticsearchAlias
     */
    public function handle(ElasticsearchAliasContract $elasticsearchAlias)
    {
        $index = (string)$this->argument('index');
        $alias = (string)$this->argument('alias');
        $version = (string)$this->argument('version');

        Log::info("The alias is created.", [
            'index' => $index,
            'alias' => $alias,
            'version' => $version
        ]);

        try {
            $elasticsearchAlias->addAlias($index, $alias, $version);

            Log::info("The alias was created.", [
                'index' => $index,
                'alias' => $alias,
                'version' => $version
            ]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), [
                'index' => $index,
                'alias' => $alias,
                'version' => $version
            ]);
        }
    }
}
