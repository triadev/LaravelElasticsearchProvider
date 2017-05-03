<?php
namespace Triadev\Es\Console\Commands\Migration;

use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Illuminate\Console\Command;
use Log;

/**
 * Class Activate
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Migration
 */
class Activate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:activate
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
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     * @param ScElasticsearchAliasContract $scElasticsearchAlias
     */
    public function handle(
        ScElasticsearchIndexContract $scElasticsearchIndex,
        ScElasticsearchAliasContract $scElasticsearchAlias
    ) {
        $index = $this->argument('index');
        $from_version = $this->argument('from_version');
        $to_version = $this->argument('to_version');

        if ($scElasticsearchIndex->existIndex([$index], $from_version)) {
            try {
                $scElasticsearchAlias->deleteAlias($index, $index, $from_version);

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

        if ($scElasticsearchIndex->existIndex([$index], $to_version)) {
            try {
                $scElasticsearchAlias->addAlias($index, $index, $to_version);

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
