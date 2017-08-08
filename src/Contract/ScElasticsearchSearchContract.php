<?php
namespace Triadev\Es\Contract;

use Triadev\Es\Models\SearchResult;

/**
 * Interface ScElasticsearchSearchContract
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Contract
 */
interface ScElasticsearchSearchContract
{
    /**
     * Search
     *
     * @param array $index
     * @param array $type
     * @param array $body
     * @param array $params
     * @param string|null $version
     * @param bool $raw
     * @return array|SearchResult
     */
    public function search(
        array $index,
        array $type,
        array $body = [],
        array $params = [],
        string $version = null,
        bool $raw = false
    );
}
