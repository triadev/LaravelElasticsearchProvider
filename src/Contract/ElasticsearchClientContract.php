<?php
namespace Triadev\Es\Contract;

use Elasticsearch\Client;

interface ElasticsearchClientContract
{
    /**
     * Get elasticsearch client
     *
     * @return Client
     */
    public function getEsClient() : Client;
}
