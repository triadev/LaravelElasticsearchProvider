<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Triadev\Es\Contract\ScElasticsearchSearchContract;
use Triadev\Es\Helper\VersionHelper;
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
     * @var Client
     */
    private $client;

    /**
     * ScElasticsearchIndex constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
            $indices[] = VersionHelper::createIndexWithVersion($i, $version);
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
