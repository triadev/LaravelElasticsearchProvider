<?php
namespace Triadev\Es;

use Monolog\Logger;
use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Contract\ElasticsearchClientContract;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;

class ElasticsearchClient implements ElasticsearchClientContract
{
    use ConfigFacade;

    /**
     * @var Client
     */
    private $client;

    /**
     * ElasticsearchClient constructor.
     */
    public function __construct()
    {
        $this->client = $this->buildElasticsearchClient();
    }

    private function buildElasticsearchClient() : Client
    {
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts([
            [
                'host' => $this->getHost(),
                'port' => $this->getPort(),
                'scheme' => $this->getScheme(),
                'user' => $this->getUser(),
                'pass' => $this->getPassword()
            ]
        ]);
        
        $clientBuilder->setRetries($this->getRetries());

        $logger = $this->getLogger();
        if ($logger) {
            $clientBuilder->setLogger($logger);
        }

        return $clientBuilder->build();
    }

    /**
     * Get elasticsearch client
     *
     * @return Client
     */
    public function getEsClient(): Client
    {
        return $this->client;
    }

    private function getLogger() :?Logger
    {
        return Log::getLogger();
    }
}
