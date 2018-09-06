<?php
namespace Triadev\Es\Console\Commands\Migration;

use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Illuminate\Console\Command;
use Log;

class Activate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tfw:es:activate
                            {index : Index}
                            {from_version : From version}
                            {to_version : To version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate the index.';

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
        $index = $this->argument('index');
        $from_version = $this->argument('from_version');
        $to_version = $this->argument('to_version');

        if ($elasticsearchIndex->existIndex([$index], $from_version)) {
            try {
                $elasticsearchAlias->deleteAlias($index, $index, $from_version);

                Log::info(sprintf(
                    "The alias (from) was deleted: %s | %s | %s",
                    $index,
                    $index,
                    $from_version
                ));
            } catch (AliasNotFoundException $e) {
                Log::error($e->getMessage());
            } catch (\Exception $e) {
                Log::error(sprintf(
                    "The alias was not deleted: %s | %s | %s | %s",
                    $index,
                    $index,
                    $from_version,
                    $e->getMessage()
                ));
            }
        }

        if ($elasticsearchIndex->existIndex([$index], $to_version)) {
            try {
                $elasticsearchAlias->addAlias($index, $index, $to_version);

                Log::info(sprintf(
                    "The alias (to) was created: %s | %s | %s",
                    $index,
                    $index,
                    $to_version
                ));
            } catch (AliasFoundException $e) {
                Log::error($e->getMessage());
            } catch (\Exception $e) {
                Log::error(sprintf(
                    "The alias (to) was not created: %s | %s | %s | %s",
                    $index,
                    $index,
                    $to_version,
                    $e->getMessage()
                ));
            }
        }
    }
}
