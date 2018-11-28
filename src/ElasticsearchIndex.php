<?php
namespace Triadev\Es;

use Triadev\Es\Business\AbstractElasticsearch;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Exception\Index\IndexFoundException;
use Triadev\Es\Exception\Index\IndexNotFoundException;

class ElasticsearchIndex extends AbstractElasticsearch implements ElasticsearchIndexContract
{
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
            $params['index'] = $this->createIndexWithVersion($index, $version);

            return $this->getElasticsearchClient()->indices()->create($params);
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
                $indices[] = $this->createIndexWithVersion($i, $version);
            }
        }

        if (!empty($indices)) {
            return $this->getElasticsearchClient()->indices()->delete([
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
        return $this->getElasticsearchClient()->indices()->delete([
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
            $indices[] = $this->createIndexWithVersion($i, $version);
        }

        return $this->getElasticsearchClient()->indices()->exists([
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
        return array_keys($this->getElasticsearchClient()->indices()->get([
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
     * @param array|null $source
     * @return array
     */
    public function reindex(
        string $index,
        string $from_version,
        string $to_version,
        array $params = [],
        ?array $source = null
    ) : array {
        $params['body'] = [
            'source' => [
                'index' => $this->createIndexWithVersion($index, $from_version)
            ],
            'dest' => [
                'index' => $this->createIndexWithVersion($index, $to_version)
            ]
        ];

        if (is_array($source)) {
            $params['body']['source']['_source'] = $source;
        }

        return $this->getElasticsearchClient()->reindex($params);
    }
}
