<?php
namespace Triadev\Es;

use Triadev\Es\Business\AbstractElasticsearch;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Contract\ElasticsearchMappingContract;
use Triadev\Es\Exception\Index\IndexNotFoundException;

class ElasticsearchMapping extends AbstractElasticsearch implements ElasticsearchMappingContract
{
    /**
     * @var ElasticsearchIndexContract
     */
    private $elasticsearchIndex;

    /**
     * ElasticsearchIndex constructor.
     * @param ElasticsearchIndexContract $elasticsearchIndex
     */
    public function __construct(ElasticsearchIndexContract $elasticsearchIndex)
    {
        $this->elasticsearchIndex = $elasticsearchIndex;
    }

    /**
     * Update mapping
     *
     * @param string $index
     * @param string $type
     * @param array $params
     * @param null|string $version
     * @return array
     * @throws IndexNotFoundException
     */
    public function updateMapping(string $index, string $type, array $params, ?string $version = null) : array
    {
        if ($this->elasticsearchIndex->existIndex([$index], $version)) {
            $params['index'] = $this->createIndexWithVersion($index, $version);
            $params['type'] = $type;

            return $this->getElasticsearchClient()->indices()->putMapping($params);
        }

        throw new IndexNotFoundException($index, $version);
    }

    /**
     * Delete mapping
     *
     * @param string $index
     * @param string $type
     * @param null|string $version
     * @return array
     * @throws IndexNotFoundException
     */
    public function deleteMapping(string $index, string $type, ?string $version = null) : array
    {
        if ($this->elasticsearchIndex->existIndex([$index], $version)) {
            return $this->getElasticsearchClient()->indices()->deleteMapping([
                'index' => $this->createIndexWithVersion($index, $version),
                'type' => $type
            ]);
        }

        throw new IndexNotFoundException($index, $version);
    }
}
