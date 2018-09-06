<?php
namespace Triadev\Es\Exception\Alias;

class AliasNotFoundException extends \Exception
{
    /**
     * AliasNotFoundException constructor.
     * @param string $index
     * @param string $alias
     * @param null|string $version
     */
    public function __construct(string $index, string $alias, ?string $version = null)
    {
        parent::__construct(sprintf(
            "The alias was not found: %s | %s | %s",
            $index,
            $alias,
            $version ? $version : ''
        ));
    }
}
