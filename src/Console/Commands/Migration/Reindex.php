<?php
namespace Triadev\Es\Console\Commands\Migration;

use Triadev\Es\Contract\ElasticsearchIndexContract;
use Illuminate\Console\Command;
use Log;

class Reindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:es:reindex
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
     * @param ElasticsearchIndexContract $elasticsearchIndex
     */
    public function handle(ElasticsearchIndexContract $elasticsearchIndex)
    {
        $index = $this->argument('index');
        $from_version = $this->argument('from_version');
        $to_version = $this->argument('to_version');

        if (!$elasticsearchIndex->existIndex([$index], $from_version)) {
            Log::error("The index (from) could not be found.", [
                'index' => $index,
                'from_version' => $from_version,
                'to_version' => $to_version
            ]);
        } elseif (!$elasticsearchIndex->existIndex([$index], $to_version)) {
            Log::error("The index (to) could not be found.", [
                'index' => $index,
                'from_version' => $from_version,
                'to_version' => $to_version
            ]);
        } else {
            try {
                $elasticsearchIndex->reindex($index, $from_version, $to_version);
            } catch (\Exception $e) {
                Log::error(sprintf(
                    "The indices could not be reindex: %s",
                    $e->getMessage()
                ), $index);
            }
        }
    }
}
