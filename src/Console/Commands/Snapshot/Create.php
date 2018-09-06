<?php
namespace Triadev\Es\Console\Commands\Snapshot;

use Illuminate\Console\Command;
use Elasticsearch\Client;
use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchClientContract;

class Create extends Command
{
    use ConfigFacade;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tfw:es:snapshot:create
                            {snapshot : Snapshot}
                            {wait_for_completion : Wait for completion (0|1)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a snapshot.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $snapshotConfig = $this->getSnapshot();

        $this->getElasticsearchClient()->snapshot()->create([
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
