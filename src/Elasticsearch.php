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
        
        $clientBuilder->setHosts(explode('|', $config, 'hosts'));
        $clientBuilder->setRetries(array_get($config, 'retries'));
    
        if ($logger = array_get($config, 'logger')) {
            $clientBuilder->setLogger($logger);
        }
        
        if ($connectionPool = array_get($config, 'connection.pool')) {
            $clientBuilder->setConnectionPool($connectionPool);
        }
    
        if ($connectionSelector = array_get($config, 'connection.selector')) {
            $clientBuilder->setSelector($connectionSelector);
        }
    
        if ($serializer = array_get($config, 'serializer')) {
            $clientBuilder->setSerializer($serializer);
        }
        
        return $clientBuilder->build();
    }
}
