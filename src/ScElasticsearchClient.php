<?php
namespace Triadev\Es;

use Monolog\Logger;
use Triadev\Es\Contract\ScElasticsearchClientContract;
use Config;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Log;
use Triadev\Es\Repository\ConfigRepository;

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
     *
     * @param ConfigRepository $config
     * @param Logger|null $logger
     */
    public function __construct(ConfigRepository $config, ?Logger $logger = null)
    {
        $this->buildElasticsearchClient($config->getConfig(), $logger);
    }

    /**
     * Build elasticsearch client
     *
     * @param array $config
     * @param Logger|null $logger
     */
    private function buildElasticsearchClient(array $config, ?Logger $logger = null)
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
        if ($logger) {
            $clientBuilder->setLogger($logger);
        }

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
