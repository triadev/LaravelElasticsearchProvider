<?php
namespace Triadev\Es\Contract;

use Triadev\Es\Exception\Index\IndexNotFoundException;

interface ElasticsearchMappingContract
{
    /**
     * Get mapping
     *
     * @param string $index
     * @param string $type
     * @param null|string $version
     * @return array
     *
     * @throws IndexNotFoundException
     */
    public function getMapping(string $index, string $type, ?string $version = null) : array;
    
    /**
     * Update mapping
     *
     * @param string $index
     * @param string $type
     * @param array $params
     * @param null|string $version
     * @return array
     *
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
     *
     * @throws IndexNotFoundException
     */
    public function deleteMapping(string $index, string $type, ?string $version = null) : array;
}
