<?php
namespace Triadev\Es\Helper;

/**
 * Class VersionHelper
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package Triadev\Es\Helper
 */
class VersionHelper
{
    /**
     * Create index with version
     *
     * @param string $index
     * @param string|null $version
     * @return string
     */
    public static function createIndexWithVersion(string $index, ?string $version = null) : string
    {
        if ($version) {
            return sprintf("%s_%s", $index, $version);
        }

        return $index;
    }
}
