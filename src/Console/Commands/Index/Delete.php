<?php
namespace Triadev\Es\Console\Commands\Index;

use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexNotFoundException;
use Illuminate\Console\Command;
use Log;

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
    protected $signature = 'sc:elasticsearch:index:delete
                            {index : Index}
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
        $index = explode(',', $this->argument('index'));
        $version = $this->argument('version');

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
