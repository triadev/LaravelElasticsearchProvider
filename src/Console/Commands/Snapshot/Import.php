<?php
namespace Triadev\Es\Console\Commands\Snapshot;

use Elasticsearch\Client;
use Illuminate\Console\Command;
use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchClientContract;

class Import extends Command
{
    use ConfigFacade;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tfw:es:snapshot:import
                            {snapshot : Snapshot}
                            {wait_for_completion : Wait for completion (0|1)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a snapshot.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $snapshotConfig = $this->getSnapshot();

        $this->getElasticsearchClient()->snapshot()->restore([
            'repository' => $snapshotConfig['repository'],
            'snapshot' => $this->argument('snapshot'),
            'body' => [],
            'wait_for_completion' => (bool)$this->argument('wait_for_completion')
        ]);
    }

    private function getElasticsearchClient() : Client
    {
        return app(ElasticsearchClientContract::class)->getEsClient();
    }
}
