<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Triadev\Es\Business\Helper\Version;
use Triadev\Es\Contract\ElasticsearchClientContract;
use Triadev\Es\Contract\ElasticsearchSearchContract;
use Triadev\Es\Models\Hit;
use Triadev\Es\Models\SearchResult;
use Triadev\Es\Models\Shards;

class ElasticsearchSearch implements ElasticsearchSearchContract
{
    use Version;

    /**
     * @var Client
     */
    private $client;
    
    /**
     * ElasticsearchSearch constructor.
     * @param ElasticsearchClientContract $clientBuilder
     */
    public function __construct(ElasticsearchClientContract $clientBuilder)
    {
        $this->client = $clientBuilder->getEsClient();
    }

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
    ) {
        $indices = [];
        foreach ($index as $i) {
            $indices[] = $this->createIndexWithVersion($i, $version);
        }

        $params['index'] = implode(',', $indices);
        $params['type'] = implode(',', $type);
        $params['body'] = $body;

        $result = $this->client->search($params);

        if (!$raw) {
            $searchResult = new SearchResult();
            $searchResult->setTook($result['took']);
            $searchResult->setTimedOut($result['timed_out']);
            $searchResult->setTotal($result['hits']['total']);
            $searchResult->setMaxScore($result['hits']['max_score']);

            if (array_key_exists('_scroll_id', $result)) {
                $searchResult->setScrollId($result['_scroll_id']);
            }
            
            $shards = new Shards();
            $shards->setTotal($result['_shards']['total']);
            $shards->setSuccessful($result['_shards']['successful']);
            $shards->setFailed($result['_shards']['failed']);
            $searchResult->setShards($shards);
            
            if ($result['hits']['total'] > 0) {
                foreach ($result['hits']['hits'] as $hit) {
                    $h = new Hit();
                    $h->setIndex($hit['_index']);
                    $h->setType($hit['_type']);
                    $h->setId($hit['_id']);
                    $h->setScore($hit['_score']);
                    $h->setSource($hit['_source']);
                    
                    if (array_key_exists('inner_hits', $hit)) {
                        $h->setInnerHits($hit['inner_hits']);
                    }
    
                    if (array_key_exists('_routing', $hit)) {
                        $h->setRouting($hit['_routing']);
                    }
    
                    if (array_key_exists('_parent', $hit)) {
                        $h->setParent($hit['_parent']);
                    }

                    $searchResult->addHit($h);
                }
            }
            
            if (array_key_exists('aggregations', $result)) {
                $searchResult->setAggs($result['aggregations']);
            }

            return $searchResult;
        }

        return $result;
    }

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
    ) {
        $body['scroll_id'] = $scrollId;
        $body['scroll'] = $scroll;

        $params['body'] = $body;

        $result = $this->client->scroll($params);

        if (!$raw) {
            $searchResult = new SearchResult();
            $searchResult->setTook($result['took']);
            $searchResult->setTimedOut($result['timed_out']);
            $searchResult->setTotal($result['hits']['total']);
            $searchResult->setMaxScore($result['hits']['max_score']);

            if (array_key_exists('_scroll_id', $result)) {
                $searchResult->setScrollId($result['_scroll_id']);
            }
            
            $shards = new Shards();
            $shards->setTotal($result['_shards']['total']);
            $shards->setSuccessful($result['_shards']['successful']);
            $shards->setFailed($result['_shards']['failed']);
            $searchResult->setShards($shards);
            
            if ($result['hits']['total'] > 0) {
                foreach ($result['hits']['hits'] as $hit) {
                    $h = new Hit();
                    $h->setIndex($hit['_index']);
                    $h->setType($hit['_type']);
                    $h->setId($hit['_id']);
                    $h->setScore($hit['_score']);
                    $h->setSource($hit['_source']);
    
                    if (array_key_exists('inner_hits', $hit)) {
                        $h->setInnerHits($hit['inner_hits']);
                    }
                    
                    if (array_key_exists('_routing', $hit)) {
                        $h->setRouting($hit['_routing']);
                    }
    
                    if (array_key_exists('_parent', $hit)) {
                        $h->setParent($hit['_parent']);
                    }

                    $searchResult->addHit($h);
                }
            }
            
            if (array_key_exists('aggregations', $result)) {
                $searchResult->setAggs($result['aggregations']);
            }

            return $searchResult;
        }

        return $result;
    }
}
