<?php
namespace Triadev\Es\Console\Commands\Index;

use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexNotFoundException;
use Illuminate\Console\Command;
use Log;

class Delete extends Command
{
    use ConfigFacade;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triadev:es:index:delete
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

        try {
            $result = $elasticsearchAlias->deleteIndex($index, $version);

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
