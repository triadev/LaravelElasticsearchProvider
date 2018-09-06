<?php
namespace Triadev\Es\Contract;

use Triadev\Es\Models\SearchResult;

interface ElasticsearchSearchContract
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

    /**
     * Scroll
     *
     * @param string $scrollId
     * @param string $scroll
     * @param array $body
     * @param array $params
     * @param bool $raw
     * @return array|SearchResult
     */
    public function scroll(
        string $scrollId,
        string $scroll,
        array $body = [],
        array $params = [],
        bool $raw = false
    );
}
