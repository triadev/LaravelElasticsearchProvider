<?php
namespace Triadev\Es\Console\Commands\Migration;

use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Illuminate\Console\Command;
use Log;

/**
 * Class Reindex
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Console\Commands\Migration
 */
class Reindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sc:elasticsearch:reindex
                            {index : Index}
                            {from_version : From version}
                            {to_version : To version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex the index.';

    /**
     * Execute the console command.
     *
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     */
    public function handle(
        ScElasticsearchIndexContract $scElasticsearchIndex
    ) {
        $index = $this->argument('index');
        $from_version = $this->argument('from_version');
        $to_version = $this->argument('to_version');

        if (!$scElasticsearchIndex->existIndex([$index], $from_version)) {
            Log::error("The index (from) could not be found.", [
                'index' => $index,
                'from_version' => $from_version,
                'to_version' => $to_version
            ]);
        } elseif (!$scElasticsearchIndex->existIndex([$index], $to_version)) {
            Log::error("The index (to) could not be found.", [
                'index' => $index,
                'from_version' => $from_version,
                'to_version' => $to_version
            ]);
        } else {
            try {
                $scElasticsearchIndex->reindex($index, $from_version, $to_version);
            } catch (\Exception $e) {
                Log::error(sprintf(
                    "The indices could not be reindex: %s",
                    $e->getMessage()
                ), $index);
            }
        }
    }
}
