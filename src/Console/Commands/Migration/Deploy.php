<?php
namespace Triadev\Es\Console\Commands\Migration;

use Illuminate\Console\Command;
use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Log;
use Triadev\Es\Exception\Index\IndexFoundException;

class Deploy extends Command
{
    use ConfigFacade;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tfw:es:deploy
                            {index : Index}
                            {migrate_data : Migrate the data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy the index to a higher version.';
    
    /**
     * Execute the console command.
     *
     * @param ElasticsearchIndexContract $elasticsearchIndex
     * @throws \Triadev\Es\Exception\Index\IndexNotFoundException
     */
    public function handle(
        ElasticsearchIndexContract $elasticsearchIndex
    ) {
        $version = $this->getDeployVersions();

        $index = $this->argument('index');
        $migrateData = (bool)$this->argument('migrate_data');

        if (array_key_exists($index, $version['indices'])) {
            $from_version = $version['indices'][$index]['from'];
            $to_version = $version['indices'][$index]['to'];

            $source = null;
            if (array_has($version['indices'][$index], 'source')) {
                if (is_array($version['indices'][$index]['source'])) {
                    $source = $version['indices'][$index]['source'];
                }
            }

            if ($this->isToVersionHigherThenOldVersion($from_version, $to_version)) {
                if ($from_version == '0.0.0' || $elasticsearchIndex->existIndex([$index], $from_version)) {
                    if (!$elasticsearchIndex->existIndex([$index], $to_version)) {
                        $indices = $this->getIndices();
                        if (array_key_exists($index, $indices)) {
                            $this->createIndex($elasticsearchIndex, $index, $indices[$index], $to_version);
                            if ($from_version != '0.0.0') {
                                if ($elasticsearchIndex->existIndex([$index], $to_version)) {
                                    if ($migrateData) {
                                        try {
                                            $result = $elasticsearchIndex->reindex(
                                                $index,
                                                $from_version,
                                                $to_version,
                                                [],
                                                $source
                                            );
                                            Log::info('The indices could be reindex.', $result);
                                        } catch (\Exception $e) {
                                            Log::error(sprintf(
                                                "The indices could not be reindex: %s",
                                                $e->getMessage()
                                            ), $index);

                                            $elasticsearchIndex->deleteIndex([$index], $to_version);
                                        }
                                    }
                                }
                            }
                        } else {
                            Log::error("The index could not be found in the config.", [
                                'index' => $index
                            ]);
                        }
                    } else {
                        Log::error("The index already exist in this version.", [
                            'index' => $index,
                            'to_version' => $to_version
                        ]);
                    }
                } else {
                    Log::error("The index (from) could not be found.", [
                        'index' => $index,
                        'from_version' => $from_version,
                        'to_version' => $to_version
                    ]);
                }
            }
        } else {
            Log::error("The deploy config for the index could not be found.", [
                'index' => $index
            ]);
        }
    }

    /**
     * Is the to version higher then the old version
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    private function isToVersionHigherThenOldVersion(string $from, string $to): bool
    {
        $from_numeric = preg_replace('![^0-9]!', '', $from);
        $to_numeric = preg_replace('![^0-9]!', '', $to);

        if ($to_numeric <= $from_numeric) {
            Log::error("The from version must be higher then the to version.", [
                'from_version' => $from,
                'to_version' => $to
            ]);

            return false;
        }

        return true;
    }

    /**
     * Create index
     *
     * @param ElasticsearchIndexContract $elasticsearchIndex
     * @param string $index
     * @param array $config
     * @param string $version
     */
    private function createIndex(
        ElasticsearchIndexContract $elasticsearchIndex,
        string $index,
        array $config,
        string $version
    ) {
        try {
            $result = $elasticsearchIndex->createIndex(
                $index,
                [
                    'body' => $config
                ],
                $version
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
    }
}
