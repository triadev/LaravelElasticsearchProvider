<?php
namespace Triadev\Es\Console\Commands\Index;

use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Illuminate\Console\Command;
use Log;

/**
 * Class DeleteAll
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Index
 */
class DeleteAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:index:delete:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all indices.';

    /**
     * Execute the console command.
     *
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     */
    public function handle(ScElasticsearchIndexContract $scElasticsearchIndex)
    {
        try {
            $result = $scElasticsearchIndex->deleteAllIndexes();

            Log::info('The indices could be deleted.', $result);
        } catch (\Exception $e) {
            Log::error(sprintf(
                "The indices could not be deleted: %s",
                $e->getMessage()
            ));
        }
    }
}
