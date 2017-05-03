<?php
namespace Triadev\Es;

use Triadev\Es\Contract\ScElasticsearchClientContract;
use Triadev\Es\Contract\ScElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexFoundException;
use Triadev\Es\Exception\Index\IndexNotFoundException;
use Triadev\Es\Helper\VersionHelper;

/**
 * Class ScElasticsearchIndex
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es
 */
class ScElasticsearchIndex implements ScElasticsearchIndexContract
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
     * Create index
     *
     * @param string $index
     * @param array $params
     * @param string|null $version
     * @return array
     * @throws IndexFoundException
     */
    public function createIndex(string $index, array $params, string $version = null) : array
    {
        if (!$this->existIndex([$index], $version)) {
            $params['index'] = VersionHelper::createIndexWithVersion($index, $version);

            return $this->esClient->getEsClient()->indices()->create($params);
        }

        throw new IndexFoundException($index, $version);
    }

    /**
     * Delete index
     *
     * @param array $index
     * @param string|null $version
     * @return array
     * @throws IndexNotFoundException
     */
    public function deleteIndex(array $index, string $version = null) : array
    {
        $indices = [];

        foreach ($index as $i) {
            if ($this->existIndex([$i], $version)) {
                $indices[] = VersionHelper::createIndexWithVersion($i, $version);
            }
        }

        if (!empty($indices)) {
            return $this->esClient->getEsClient()->indices()->delete([
                'index' => implode(',', $indices)
            ]);
        }

        throw new IndexNotFoundException(implode(',', $index), $version);
    }

    /**
     * Delete all indexes
     *
     * @return array
     */
    public function deleteAllIndexes() : array
    {
        return $this->esClient->getEsClient()->indices()->delete([
            'index' => '_all'
        ]);
    }

    /**
     * Exist index
     *
     * @param array $index
     * @param string|null $version
     * @return bool
     */
    public function existIndex(array $index, ?string $version = null) : bool
    {
        $indices = [];

        foreach ($index as $i) {
            $indices[] = VersionHelper::createIndexWithVersion($i, $version);
        }

        return $this->esClient->getEsClient()->indices()->exists([
            'index' => implode(',', $indices)
        ]);
    }

    /**
     * Get versioned indices
     *
     * @param string $index
     * @return array
     */
    public function getVersionedIndices(string $index) : array
    {
        return array_keys($this->esClient->getEsClient()->indices()->get([
            'index' => sprintf("%s_*", $index)
        ]));
    }

    /**
     * Reindex
     *
     * @param string $index
     * @param string $from_version
     * @param string $to_version
     * @param array $params
     * @return array
     */
    public function reindex(string $index, string $from_version, string $to_version, array $params = []) : array
    {
        $params['body'] = [
            'source' => [
                'index' => VersionHelper::createIndexWithVersion($index, $from_version)
            ],
            'dest' => [
                'index' => VersionHelper::createIndexWithVersion($index, $to_version)
            ]
        ];

        return $this->esClient->getEsClient()->reindex($params);
    }
}
