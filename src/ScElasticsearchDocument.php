<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Triadev\Es\Contract\ScElasticsearchDocumentContract;
use Triadev\Es\Helper\VersionHelper;

/**
 * Class ScElasticsearchDocument
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es
 */
class ScElasticsearchDocument implements ScElasticsearchDocumentContract
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
     * Create document
     *
     * @param string $index
     * @param string $type
     * @param string|null $version
     * @param array $params
     * @param null|string $id
     * @return array
     */
    public function createDocument(
        string $index,
        string $type,
        string $version = null,
        array $params = [],
        ?string $id = null
    ) : array {
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;

        if ($id) {
            $params['id'] = $id;
        }

        return $this->client->index($params);
    }

    /**
     * Delete document
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @param string|null $version
     * @param array $params
     * @return array
     */
    public function deleteDocument(
        string $index,
        string $type,
        string $id,
        string $version = null,
        array $params = []
    ) : array {
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;

        return $this->client->delete($params);
    }

    /**
     * Get document
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @param string|null $version
     * @return array
     */
    public function getDocument(
        string $index,
        string $type,
        string $id,
        string $version = null
    ) : array {
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;

        return $this->client->get($params);
    }

    /**
     * Mget documents
     *
     * @param string $index
     * @param string $type
     * @param array $params
     * @param string|null $version
     * @return array
     */
    public function mgetDocuments(
        string $index,
        string $type,
        array $params = [],
        string $version = null
    ) : array {
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;

        return $this->client->mget($params);
    }

    /**
     * Exist document
     *
     * @param string $index
     * @param string $type
     * @param string $id
     * @param array $params
     * @param string|null $version
     * @return bool
     */
    public function existDocument(
        string $index,
        string $type,
        string $id,
        array $params = [],
        string $version = null
    ) : bool {
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;

        return (bool)$this->client->exists($params);
    }
}
