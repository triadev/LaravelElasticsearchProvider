<?php
namespace Triadev\Es\Contract;

use Triadev\Es\Exception\Index\IndexFoundException;
use Triadev\Es\Exception\Index\IndexNotFoundException;

/**
 * Interface ScElasticsearchIndexContract
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Contract
 */
interface ScElasticsearchIndexContract
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
    public function createIndex(string $index, array $params, string $version = null) : array;

    /**
     * Delete index
     *
     * @param array $index
     * @param string|null $version
     * @return array
     * @throws IndexNotFoundException
     */
    public function deleteIndex(array $index, string $version = null) : array;

    /**
     * Delete all indexes
     *
     * @return array
     */
    public function deleteAllIndexes() : array;

    /**
     * Exist index
     *
     * @param array $index
     * @param string|null $version
     * @return bool
     */
    public function existIndex(array $index, string $version = null) : bool;

    /**
     * Get versioned indices
     *
     * @param string $index
     * @return array
     */
    public function getVersionedIndices(string $index) : array;

    /**
     * Reindex
     *
     * @param string $index
     * @param string $from_version
     * @param string $to_version
     * @param array $params
     * @return array
     */
    public function reindex(string $index, string $from_version, string $to_version, array $params = []) : array;
}
