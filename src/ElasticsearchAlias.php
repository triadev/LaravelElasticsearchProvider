<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Triadev\Es\Business\Helper\Version;
use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchClientContract;
use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;

class ElasticsearchAlias implements ElasticsearchAliasContract
{
    use Version;

    /**
     * @var Client
     */
    private $client;
    
    /**
     * ElasticsearchAlias constructor.
     * @param ElasticsearchClientContract $clientBuilder
     */
    public function __construct(ElasticsearchClientContract $clientBuilder)
    {
        $this->client = $clientBuilder->getEsClient();
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
                'index' => $this->createIndexWithVersion($index, $version),
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
                'index' => $this->createIndexWithVersion($index, $version),
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
            $indices[] = $this->createIndexWithVersion($i, $version);
        }
        $params['index'] = implode(',', $indices);

        return $this->client->indices()->existsAlias($params);
    }
}
