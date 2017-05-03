<?php
namespace Triadev\Es;

use Triadev\Es\Contract\ScElasticsearchClientContract;
use Config;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Log;

/**
 * Class ScElasticsearchClient
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es
 */
class ScElasticsearchClient implements ScElasticsearchClientContract
{
    /**
     * @var Client
     */
    private $client;

    /**
     * FlElasticsearch constructor.
     */
    public function __construct()
    {
        $config = Config::get('sc-elasticsearch');

        $this->buildElasticsearchClient($config);
    }

    /**
     * Build elasticsearch client
     *
     * @param array $config
     */
    private function buildElasticsearchClient(array $config)
    {
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts([
            [
                'host' => $config['host'],
                'port' => $config['port'],
                'scheme' => $config['scheme'],
                'user' => $config['user'],
                'pass' => $config['pass']
            ]
        ]);
        $clientBuilder->setRetries($config['config']['retries']);
        $clientBuilder->setLogger(Log::getMonolog());

        $this->client = $clientBuilder->build();
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
}
