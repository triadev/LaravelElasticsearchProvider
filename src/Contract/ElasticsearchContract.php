<?php
namespace Triadev\Es\Contract;

use Elasticsearch\Client;

interface ElasticsearchContract
{
    /**
     * Get client
     *
     * @return Client
     */
    public function getClient() : Client;
}
