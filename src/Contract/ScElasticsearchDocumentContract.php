<?php
namespace Triadev\Es\Contract;

/**
 * Interface ScElasticsearchDocumentContract
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Contract
 */
interface ScElasticsearchDocumentContract
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
    ) : array;

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
    ) : array;

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
    ) : array;

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
    ) : array;

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
    ) : array;

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
    ) : bool;
}
