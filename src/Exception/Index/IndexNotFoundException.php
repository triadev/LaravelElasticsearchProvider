<?php
namespace Triadev\Es\Exception\Index;

class IndexNotFoundException extends \Exception
{
    /**
     * IndexNotFoundException constructor.
     * @param string $index
     * @param null|string $version
     */
    public function __construct(string $index, ?string $version = null)
    {
        parent::__construct(sprintf(
            "The index was not found: %s | %s",
            $index,
            $version ? $version : ''
        ));
    }
}
