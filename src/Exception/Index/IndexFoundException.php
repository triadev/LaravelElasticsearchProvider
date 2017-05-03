<?php
namespace Triadev\Es\Exception\Index;

/**
 * Class IndexFoundException
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Exception\Index
 */
class IndexFoundException extends \Exception
{
    /**
     * IndexFoundException constructor.
     * @param string $index
     * @param null|string $version
     */
    public function __construct(string $index, ?string $version = null)
    {
        parent::__construct(sprintf(
            "The index found: %s | %s",
            $index,
            $version ? $version : ''
        ));
    }
}
