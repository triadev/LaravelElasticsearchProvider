<?php
namespace Triadev\Es\Console\Commands\Index;

use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexFoundException;
use Illuminate\Console\Command;
use Config;
use Log;

/**
 * Class Create
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Index
 */
class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:elasticsearch:index:create
                            {index : Index (_all for all)}
                            {version : Version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create one or more indices.';

    /**
     * Execute the console command.
     *
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     */
    public function handle(ScElasticsearchIndexContract $scElasticsearchIndex)
    {
        $version = $this->argument('version');

        $indices = Config::get('sc-elasticsearch')['config']['indices'];
        
        if ($this->argument('index') == '_all') {
            $index = array_keys($indices);
        } else {
            $index = explode(',', $this->argument('index'));
        }
        
        foreach ($index as $i) {
            if (!array_key_exists($i, $indices)) {
                Log::info(sprintf("The index could not be found: %s", $i));
                continue;
            }

            try {
                $result = $scElasticsearchIndex->createIndex(
                    $i,
                    [
                        'body' => $indices[$i]
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
}
