<?php
namespace Triadev\Es\Contract;

use Triadev\Es\Exception\Alias\AliasFoundException;
use Triadev\Es\Exception\Alias\AliasNotFoundException;

interface ElasticsearchAliasContract
{
    /**
     * Add an alias
     *
     * @param string $index
     * @param string $alias
     * @param string|null $version
     * @return array
     * @throws AliasFoundException
     */
    public function addAlias(string $index, string $alias, string $version = null) : array;

    /**
     * Delete an alias
     *
     * @param string $index
     * @param string $alias
     * @param string|null $version
     * @return array
     * @throws AliasNotFoundException
     */
    public function deleteAlias(string $index, string $alias, string $version = null) : array;

    /**
     * Exist alias
     *
     * @param array $index
     * @param array $alias
     * @param string|null $version
     * @param array $params
     * @return bool
     */
    public function existAlias(array $index, array $alias, ?string $version = null, array $params = []) : bool;
}
