<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Triadev\Es\Contract\ScElasticsearchAliasContract;
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
            return $this->client->indices()->putAlias([
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
            return $this->client->indices()->deleteAlias([
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

        return $this->client->indices()->existsAlias($params);
    }
}
