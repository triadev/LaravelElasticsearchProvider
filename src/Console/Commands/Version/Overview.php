<?php
namespace Triadev\Es\Console\Commands\Version;

use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Illuminate\Console\Command;

/**
 * Class Overview
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Version
 */
class Overview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:version:overview
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
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     * @param ScElasticsearchAliasContract $scElasticsearchAlias
     */
    public function handle(
        ScElasticsearchIndexContract $scElasticsearchIndex,
        ScElasticsearchAliasContract $scElasticsearchAlias
    ) {
        $indices = $scElasticsearchIndex->getVersionedIndices($this->argument('index'));

        $headers = ['Name', 'Version', 'Active'];

        $rows = [];

        foreach ($indices as $index) {
            if (preg_match('/(?<name>[a-z]+)_(?<version>[0-9]+.[0-9]+.[0-9]+)/', $index, $matches)) {
                $rows[$matches['version']] = [
                    $matches['name'],
                    $matches['version'],
                    $scElasticsearchAlias->existAlias(
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
