<?php
namespace Triadev\Es\Console\Commands\Version;

use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Illuminate\Console\Command;

class Overview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:es:version:overview
                            {index : Index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get an overview of the version of an index.';

    /**
     * Execute the console command.
     *
     * @param ElasticsearchIndexContract $elasticsearchIndex
     * @param ElasticsearchAliasContract $elasticsearchAlias
     */
    public function handle(
        ElasticsearchIndexContract $elasticsearchIndex,
        ElasticsearchAliasContract $elasticsearchAlias
    ) {
        $indices = $elasticsearchIndex->getVersionedIndices($this->argument('index'));

        $headers = ['Name', 'Version', 'Active'];

        $rows = [];

        foreach ($indices as $index) {
            if (preg_match('/(?<name>[a-z]+)_(?<version>[0-9]+.[0-9]+.[0-9]+)/', $index, $matches)) {
                $rows[$matches['version']] = [
                    $matches['name'],
                    $matches['version'],
                    $elasticsearchAlias->existAlias(
                        [$matches['name']],
                        [$matches['name']],
                        $matches['version']
                    ) ? 'true' : 'false'
                ];
            }
        }

        $this->table($headers, $rows);
    }
}
