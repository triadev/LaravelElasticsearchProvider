<?php
namespace Triadev\Es\Repository;

/**
 * Class ConfigRepository
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 */
class ConfigRepository
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * ConfigRepository constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the full config
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }
}
