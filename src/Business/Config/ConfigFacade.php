<?php
namespace Triadev\Es\Business\Config;

trait ConfigFacade
{
    /**
     * @return string
     */
    public function getHost() : string
    {
        return $this->getConfig()['host'];
    }

    /**
     * @return int
     */
    public function getPort() : int
    {
        return $this->getConfig()['port'];
    }

    /**
     * @return string
     */
    public function getScheme() : string
    {
        return $this->getConfig()['scheme'];
    }

    /**
     * @return string
     */
    public function getUser() : string
    {
        return $this->getConfig()['user'];
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->getConfig()['pass'];
    }

    /**
     * @return int
     */
    public function getRetries() : int
    {
        return $this->getConfig()['config']['retries'];
    }

    /**
     * @return array
     */
    public function getIndices() : array
    {
        return $this->getConfig()['config']['indices'];
    }

    /**
     * @return array
     */
    public function getDeployVersions() : array
    {
        return $this->getConfig()['deploy']['version'];
    }

    /**
     * @return array
     */
    public function getSnapshot() : array
    {
        return $this->getConfig()['snapshot'];
    }

    private function getConfig() : array
    {
        return config('triadev-elasticsearch');
    }
}
