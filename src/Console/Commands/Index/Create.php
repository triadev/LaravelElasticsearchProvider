<?php
namespace Triadev\Es\Console\Commands\Index;

use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexFoundException;
use Illuminate\Console\Command;
use Log;

class Create extends Command
{
    use ConfigFacade;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:es:index:create
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
     * @param ElasticsearchIndexContract $elasticsearchAlias
     */
    public function handle(ElasticsearchIndexContract $elasticsearchAlias)
    {
        $version = $this->argument('version');

        $indices = $this->getIndices();

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
                $result = $elasticsearchAlias->createIndex(
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
