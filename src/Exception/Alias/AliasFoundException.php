<?php
namespace Triadev\Es\Exception\Alias;

/**
 * Class AliasFoundException
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Exception\Alias
 */
class AliasFoundException extends \Exception
{
    /**
     * AliasFoundException constructor.
     * @param string $index
     * @param string $alias
     * @param null|string $version
     */
    public function __construct(string $index, string $alias, ?string $version = null)
    {
        parent::__construct(sprintf(
            "The alias found: %s | %s | %s",
            $index,
            $alias,
            $version ? $version : ''
        ));
    }
}
