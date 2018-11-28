<?php
namespace Triadev\Es;

use Triadev\Es\Business\AbstractElasticsearch;
use Triadev\Es\Contract\ElasticsearchDocumentContract;

class ElasticsearchDocument extends AbstractElasticsearch implements ElasticsearchDocumentContract
{
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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;

        if ($id) {
            $params['id'] = $id;
        }

        $result = $this->getElasticsearchClient()->index($params);

        return $result;
    }

    /**
     * Update document
     *
     * @param string $index
     * @param string $type
     * @param string|null $version
     * @param array $params
     * @param null|string $id
     * @return array
     */
    public function updateDocument(
        string $index,
        string $type,
        string $version = null,
        array $params = [],
        ?string $id = null
    ) : array {
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;

        if ($id) {
            $params['id'] = $id;
        }

        $result = $this->getElasticsearchClient()->update($params);

        return $result;
    }
    
    /**
     * Create documents with bulk
     *
     * @param string $index
     * @param string $type
     * @param string|null $version
     * @param array $body
     * @param array|null $ids
     * @param array|null $parents
     * @return array
     */
    public function createDocumentsWithBulk(
        string $index,
        string $type,
        string $version = null,
        array $body = [],
        ?array $ids = null,
        ?array $parents = null
    ) : array {
        $params = [];

        foreach ($body as $key => $b) {
            $esIndex = [
                '_index' => $this->createIndexWithVersion($index, $version),
                '_type' => $type,
            ];

            if (is_array($ids) && count($body) == count($ids)) {
                $esIndex['_id'] = $ids[$key];
            }
            
            if (is_array($parents) && count($body) == count($parents)) {
                $esIndex['parent'] = $parents[$key];
            }

            $params['body'][] = [
                'index' => $esIndex
            ];

            $params['body'][] = $b;
        }

        $result = $this->getElasticsearchClient()->bulk($params);

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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;

        $result = $this->getElasticsearchClient()->delete($params);

        return $result;
    }
    
    /**
     * Delete documents with bulk
     *
     * @param string $index
     * @param string $type
     * @param array $ids
     * @param string|null $version
     * @param array|null $parents
     * @return array
     */
    public function deleteDocumentsWithBulk(
        string $index,
        string $type,
        array $ids,
        string $version = null,
        ?array $parents = null
    ) : array {
        $params = [];
        
        foreach ($ids as $key => $id) {
            $esIndex = [
                '_index' => $this->createIndexWithVersion($index, $version),
                '_type' => $type,
                '_id' => $id
            ];
            
            if (is_array($parents) && count($ids) == count($parents)) {
                $esIndex['parent'] = $parents[$key];
            }
            
            $params['body'][] = [
                'delete' => $esIndex
            ];
        }
        
        $result = $this->getElasticsearchClient()->bulk($params);
        
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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['body'] = $body;

        $result = $this->getElasticsearchClient()->deleteByQuery($params);

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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;

        $result = $this->getElasticsearchClient()->get($params);

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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;

        $result = $this->getElasticsearchClient()->mget($params);

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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;
        $params['id'] = $id;

        $result = (bool)$this->getElasticsearchClient()->exists($params);

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
        $params['index'] = $this->createIndexWithVersion($index, $version);
        $params['type'] = $type;
    
        $result = $this->getElasticsearchClient()->count($params);
    
        return $result['count'];
    }
}
