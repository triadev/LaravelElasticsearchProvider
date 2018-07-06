<?php
namespace Triadev\Es;

use Elasticsearch\Client;
use Triadev\Es\Contract\ScElasticsearchDocumentContract;
use Triadev\Es\Helper\MetricHelper;
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
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        
        if ($id) {
            $params['id'] = $id;
        }
        
        $result = $this->client->index($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'createDocument'
        );
        
        return $result;
    }
    
    /**
     * Update document
     *
     * @param string $index
     * @param string $type
     * @param string|null $version
     * @param array $params
     * @param string $id
     * @return array
     */
    public function updateDocument(
        string $index,
        string $type,
        string $version = null,
        array $params = [],
        string $id
    ) : array {
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;
        
        $result = $this->client->update($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'updateDocument'
        );
        
        return $result;
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
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;
        
        $result = $this->client->delete($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'deleteDocument'
        );
        
        return $result;
    }
    
    /**
     * Delete documents by query
     *
     * @param string $index
     * @param string $type
     * @param array $body
     * @param string|null $version
     * @param array $params
     * @return array
     */
    public function deleteDocumentsByQuery(
        string $index,
        string $type,
        array $body = [],
        string $version = null,
        array $params = []
    ): array {
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['body'] = $body;
        
        $result = $this->client->deleteByQuery($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'deleteDocumentsByQuery'
        );
        
        return $result;
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
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;
        
        $result = $this->client->get($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'getDocument'
        );
        
        return $result;
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
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        
        $result = $this->client->mget($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'mgetDocuments'
        );
        
        return $result;
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
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;
        
        $result = (bool)$this->client->exists($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'existDocument'
        );
        
        return $result;
    }
    
    /**
     * Count document
     *
     * @param string $index
     * @param string $type
     * @param array $params
     * @param string|null $version
     * @return int
     */
    public function countDocuments(
        string $index,
        string $type,
        array $params = [],
        string $version = null
    ): int {
        $requestStartTime = MetricHelper::getRequestStartTime();
        
        $params['index'] = VersionHelper::createIndexWithVersion($index, $version);
        $params['type'] = $type;
        
        $result = $this->client->count($params);
        
        MetricHelper::setRequestDurationHistogram(
            MetricHelper::getRequestEndTimeInMilliseconds($requestStartTime),
            'countDocuments'
        );
        
        return $result['count'];
    }
}
