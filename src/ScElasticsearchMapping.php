<?php
namespace Triadev\Es;

use Triadev\Es\Contract\ScElasticsearchClientContract;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Contract\ScElasticsearchMappingContract;
use Triadev\Es\Exception\Index\IndexNotFoundException;
use Triadev\Es\Helper\VersionHelper;

/**
 * Class ScElasticsearchMapping
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es
 */
class ScElasticsearchMapping implements ScElasticsearchMappingContract
{
    /**
     * @var ScElasticsearchClientContract
     */
    private $esClient;

    /**
     * @var ScElasticsearchIndexContract
     */
    private $scElasticsearchIndex;

    /**
     * ScElasticsearchIndex constructor.
     * @param ScElasticsearchClientContract $esClient
     * @param ScElasticsearchIndexContract $scElasticsearchIndex
     */
    public function __construct(
        ScElasticsearchClientContract $esClient,
        ScElasticsearchIndexContract $scElasticsearchIndex
    ) {
        $this->esClient = $esClient;
        $this->scElasticsearchIndex = $scElasticsearchIndex;
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
        if ($this->scElasticsearchIndex->existIndex([$index], $version)) {
            $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
            $params['type'] = $type;

            return $this->esClient->getEsClient()->indices()->putMapping($params);
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
        if ($this->scElasticsearchIndex->existIndex([$index], $version)) {
            return $this->esClient->getEsClient()->indices()->deleteMapping([
                'index' => VersionHelper::createIndexWithVersion($index, $version),
                'type' => $type
            ]);
        }

        throw new IndexNotFoundException($index, $version);
    }
}
