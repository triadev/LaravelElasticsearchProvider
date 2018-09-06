<?php
namespace Triadev\Es\Console\Commands\Migration;

use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Triadev\Es\Exception\Index\IndexFoundException;
use Illuminate\Console\Command;
use Log;

class Migrate extends Command
{
    use ConfigFacade;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tfw:es:migrate
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
        $activate = (bool)$this->argument('activate');

        if (!$elasticsearchIndex->existIndex([$index], $from_version)) {
            Log::error("The index (from) could not be found.", [
                'index' => $index,
                'from_version' => $from_version,
                'to_version' => $to_version
            ]);
        } else {
            if ($elasticsearchIndex->existIndex([$index], $to_version)) {
                Log::error("The index (to) already exist.", [
                    'index' => $index,
                    'from_version' => $from_version,
                    'to_version' => $to_version
                ]);
            } else {
                $indices = $this->getIndices();
                if (!array_key_exists($index, $indices)) {
                    Log::info(sprintf("The index could not be found: %s", $index));
                } else {
                    try {
                        $result = $elasticsearchIndex->createIndex(
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

                    if ($elasticsearchIndex->existIndex([$index], $to_version)) {
                        try {
                            $result = $elasticsearchIndex->reindex($index, $from_version, $to_version);
                            Log::info('The indices could be reindex.', $result);

                            if ($activate) {
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
