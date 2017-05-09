<?php
namespace Triadev\Es;

use Triadev\Es\Contract\ScElasticsearchClientContract;
use Triadev\Es\Contract\ScElasticsearchSearchContract;
use Triadev\Es\Models\Hit;
use Triadev\Es\Models\SearchResult;
use Triadev\Es\Models\Shards;

/**
 * Class ScElasticsearchSearch
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es
 */
class ScElasticsearchSearch implements ScElasticsearchSearchContract
{
    /**
     * @var ScElasticsearchClientContract
     */
    private $esClient;

    /**
     * ScElasticsearchIndex constructor.
     * @param ScElasticsearchClientContract $esClient
     */
    public function __construct(ScElasticsearchClientContract $esClient)
    {
        $this->esClient = $esClient;
    }

    /**
     * Search
     *
     * @param array $index
     * @param array $type
     * @param array $body
     * @param array $params
     * @param bool $raw
     * @return array|SearchResult
     */
    public function search(
        array $index,
        array $type,
        array $body = [],
        array $params = [],
        bool $raw = false
    ) {
        $params['index'] = implode(',', $index);
        $params['type'] = implode(',', $type);
        $params['body'] = $body;

        $result = $this->esClient->getEsClient()->search($params);
        
        if (!$raw) {
            $searchResult = new SearchResult();
            $searchResult->setTook($result['took']);
            $searchResult->setTimedOut($result['timed_out']);
            $searchResult->setTotal($result['hits']['total']);
            $searchResult->setMaxScore($result['hits']['max_score']);

            // Shards
            $shards = new Shards();
            $shards->setTotal($result['_shards']['total']);
            $shards->setSuccessful($result['_shards']['successful']);
            $shards->setFailed($result['_shards']['failed']);
            $searchResult->setShards($shards);

            // Hits
            if ($result['hits']['total'] > 0) {
                foreach ($result['hits']['hits'] as $hit) {
                    $h = new Hit();
                    $h->setIndex($hit['_index']);
                    $h->setType($hit['_type']);
                    $h->setId($hit['_id']);
                    $h->setScore($hit['_score']);
                    $h->setSource($hit['_source']);

                    $searchResult->addHit($h);
                }
            }

            // Aggregations
            if (array_key_exists('aggregations', $result)) {
                $searchResult->setAggs($result['aggregations']);
            }

            return $searchResult;
        }

        return $result;
    }
}
