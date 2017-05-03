<?php
namespace Triadev\Es\Contract;

use Elasticsearch\Client;

/**
 * Interface ScElasticsearchClientContract
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Contract
 */
interface ScElasticsearchClientContract
{
    /**
     * Get elasticsearch client
     *
     * @return Client
     */
    public function getEsClient() : Client;
}
