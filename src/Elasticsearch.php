<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Triadev\Es\Contract\ElasticsearchContract;

class Elasticsearch implements ElasticsearchContract
{
    /** @var Client */
    private $client;
    
    /**
     * Get client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        if (!$this->client) {
            $this->client = $this->buildElasticsearchClient();
        }
    
        return $this->client;
    }
    
    private function buildElasticsearchClient() : Client
    {
        $config = config('triadev-elasticsearch');
        
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts([
            [
                'host' => array_get($config, 'host'),
                'port' => array_get($config, 'port'),
                'scheme' => array_get($config, 'scheme'),
                'user' => array_get($config, 'user'),
                'pass' => array_get($config, 'password')
            ]
        ]);
        
        $clientBuilder->setRetries(array_get($config, 'retries'));
        
        return $clientBuilder->build();
    }
}
