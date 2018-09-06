<?php
namespace Triadev\Es\Console\Commands\Snapshot\Repository;

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
    protected $signature = 'tfw:es:snapshot:repository:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a snapshot repository.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $snapshotConfig = $this->getSnapshot();

        $this->getElasticsearchClient()->snapshot()->createRepository([
            'repository' => $snapshotConfig['repository'],
            'body' => [
                'type' => $snapshotConfig['type'],
                'settings' => $snapshotConfig['settings']
            ]
        ]);
    }

    private function getElasticsearchClient() : Client
    {
        return app(ElasticsearchClientContract::class)->getEsClient();
    }
}
