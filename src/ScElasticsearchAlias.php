<?php
namespace Triadev\Es;

use Triadev\Es\Contract\ScElasticsearchAliasContract;
use Triadev\Es\Contract\ScElasticsearchClientContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;
use Triadev\Es\Helper\VersionHelper;

/**
 * Class ScElasticsearchAlias
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es
 */
class ScElasticsearchAlias implements ScElasticsearchAliasContract
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
     * Add an alias
     *
     * @param string $index
     * @param string $alias
     * @param string|null $version
     * @return array
     * @throws AliasFoundException
     */
    public function addAlias(string $index, string $alias, string $version = null) : array
    {
        if (!$this->existAlias([$index], [$alias], $version)) {
            return $this->esClient->getEsClient()->indices()->putAlias([
                'index' => VersionHelper::createIndexWithVersion($index, $version),
                'name' => $alias
            ]);
        }

        throw new AliasFoundException($index, $alias, $version);
    }

    /**
     * Delete an alias
     *
     * @param string $index
     * @param string $alias
     * @param string|null $version
     * @return array
     * @throws AliasNotFoundException
     */
    public function deleteAlias(string $index, string $alias, string $version = null) : array
    {
        if ($this->existAlias([$index], [$alias], $version)) {
            return $this->esClient->getEsClient()->indices()->deleteAlias([
                'index' => VersionHelper::createIndexWithVersion($index, $version),
                'name' => $alias
            ]);
        }

        throw new AliasNotFoundException($index, $alias, $version);
    }

    /**
     * Exist alias
     *
     * @param array $index
     * @param array $alias
     * @param string|null $version
     * @param array $params
     * @return bool
     */
    public function existAlias(array $index, array $alias, ?string $version = null, array $params = []) : bool
    {
        $params['name'] = implode(',', $alias);

        $indices = [];
        foreach ($index as $i) {
            $indices[] = VersionHelper::createIndexWithVersion($i, $version);
        }
        $params['index'] = implode(',', $indices);

        return $this->esClient->getEsClient()->indices()->existsAlias($params);
    }
}
