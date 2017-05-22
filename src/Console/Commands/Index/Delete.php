<?php
namespace Triadev\Es\Console\Commands\Index;

use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexNotFoundException;
use Illuminate\Console\Command;
use Log;
use Config;

/**
 * Class Delete
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Index
 */
class Delete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:elasticsearch:index:delete
                            {index : Index (_all for all)}
                            {version : Version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete one or more indices.';

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
        
        try {
            $result = $scElasticsearchIndex->deleteIndex($index, $version);

            Log::info('The indices could be deleted.', $result);
        } catch (IndexNotFoundException $e) {
            Log::error("The indices could not be found.", $index);
        } catch (\Exception $e) {
            Log::error(sprintf(
                "The indices could not be deleted: %s",
                $e->getMessage()
            ), $index);
        }
    }
}
