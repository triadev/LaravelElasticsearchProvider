<?php
namespace Triadev\Es\Business\Helper;

trait Version
{
    /**
     * Create index with version
     *
     * @param string $index
     * @param null|string $version
     * @return string
     */
    public function createIndexWithVersion(string $index, ?string $version = null) : string
    {
        if ($version) {
            return sprintf("%s_%s", $index, $version);
        }

        return $index;
    }
}
