<?php
namespace Triadev\Es\Contract;

use Triadev\Es\Exception\Index\IndexNotFoundException;

/**
 * Interface ScElasticsearchMappingContract
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Contract
 */
interface ScElasticsearchMappingContract
{
    /**
     * Update mapping
     *
     * @param string $index
     * @param string $type
     * @param array $params
     * @param null|string $version
     * @return array
     * @throws IndexNotFoundException
     */
    public function updateMapping(string $index, string $type, array $params, ?string $version = null) : array;

    /**
     * Delete mapping
     *
     * @param string $index
     * @param string $type
     * @param null|string $version
     * @return array
     * @throws IndexNotFoundException
     */
    public function deleteMapping(string $index, string $type, ?string $version = null) : array;
}
