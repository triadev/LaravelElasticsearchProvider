<?php
namespace Triadev\Es\Business;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Triadev\Es\Business\Config\ConfigFacade;
use Triadev\Es\Business\Helper\Version;

abstract class AbstractElasticsearch
{
    use ConfigFacade,
        Version;
    
    /** @var Client */
    private $elasticsearchClient;
    
    /**
     * Get elasticsearch client
     *
     * @return Client
     */
    protected function getElasticsearchClient() : Client
    {
        if (!$this->elasticsearchClient) {
            $this->elasticsearchClient = $this->buildElasticsearchClient();
        }
        
        return $this->elasticsearchClient;
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
        
        return $clientBuilder->build();
    }
}
