<?php
namespace Triadev\Es\Console\Commands\Migration;

use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Triadev\Es\Exception\Index\IndexFoundException;
use Illuminate\Console\Command;
use Log;
use Config;

/**
 * Class Migrate
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Migration
 */
class Migrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:migrate
                            {index : Index}
                            {from_version : From version}
                            {to_version : To version}
                            {activate : Activate (0|1)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the index.';

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
        $activate = (bool)$this->argument('activate');

        if (!$scElasticsearchIndex->existIndex([$index], $from_version)) {
            Log::error("The index (from) could not be found.", [
                'index' => $index,
                'from_version' => $from_version,
                'to_version' => $to_version
            ]);
        } else {
            if ($scElasticsearchIndex->existIndex([$index], $to_version)) {
                Log::error("The index (to) already exist.", [
                    'index' => $index,
                    'from_version' => $from_version,
                    'to_version' => $to_version
                ]);
            } else {
                $indices = Config::get('sc-elasticsearch')['config']['indices'];
                if (!array_key_exists($index, $indices)) {
                    Log::info(sprintf("The index could not be found: %s", $index));
                } else {
                    try {
                        $result = $scElasticsearchIndex->createIndex(
                            $index,
                            [
                                'body' => $indices[$index]
                            ],
                            $to_version
                        );

                        Log::info('The indices could be created.', $result);
                    } catch (IndexFoundException $e) {
                        Log::error($e->getMessage());
                    } catch (\Exception $e) {
                        Log::error(sprintf(
                            "The indices could not be created: %s",
                            $e->getMessage()
                        ), $index);
                    }

                    if ($scElasticsearchIndex->existIndex([$index], $to_version)) {
                        try {
                            $result = $scElasticsearchIndex->reindex($index, $from_version, $to_version);
                            Log::info('The indices could be reindex.', $result);

                            if ($activate) {
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
                        } catch (\Exception $e) {
                            Log::error(sprintf(
                                "The indices could not be reindex: %s",
                                $e->getMessage()
                            ), $index);
                        }
                    }
                }
            }
        }
    }
}
