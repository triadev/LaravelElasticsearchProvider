<?php
namespace Triadev\Es\Console\Commands\Migration;

use Illuminate\Console\Command;
use Config;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Log;
use Triadev\Es\Exception\Index\IndexFoundException;

/**
 * Class Deploy
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 * @package Triadev\Es\Console\Commands\Migration
 */
class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:elasticsearch:deploy
                            {index : Index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy the index to a higher version.';

    /**
     * Execute the console command.
     *
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     */
    public function handle(
        ScElasticsearchIndexContract $scElasticsearchIndex
    ) {
        $version = Config::get('sc-elasticsearch')['deploy']['version'];

        $index = $this->argument('index');

        if (array_key_exists($index, $version['indices'])) {
            $from_version = $version['indices'][$index]['from'];
            $to_version = $version['indices'][$index]['to'];
            if ($this->isToVersionHigherThenOldVersion($from_version, $to_version)) {
                if ($scElasticsearchIndex->existIndex([$index], $from_version)) {
                    if (!$scElasticsearchIndex->existIndex([$index], $to_version)) {
                        $indices = Config::get('sc-elasticsearch')['config']['indices'];
                        if (array_key_exists($index, $indices)) {
                            $this->createIndex($scElasticsearchIndex, $index, $indices[$index], $to_version);
                            if ($scElasticsearchIndex->existIndex([$index], $to_version)) {
                                try {
                                    $result = $scElasticsearchIndex->reindex($index, $from_version, $to_version);
                                    Log::info('The indices could be reindex.', $result);
                                } catch (\Exception $e) {
                                    Log::error(sprintf(
                                        "The indices could not be reindex: %s",
                                        $e->getMessage()
                                    ), $index);

                                    $scElasticsearchIndex->deleteIndex([$index], $to_version);
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
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     * @param string $index
     * @param array $config
     * @param string $version
     */
    private function createIndex(
        ScElasticsearchIndexContract $scElasticsearchIndex,
        string $index,
        array $config,
        string $version
    ) {
        try {
            $result = $scElasticsearchIndex->createIndex(
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
