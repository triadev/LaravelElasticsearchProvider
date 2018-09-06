<?php
namespace Tests\Unit\Business\Config;

use Tests\TestCase;
use Triadev\Es\Business\Config\ConfigFacade;

class ConfigFacadeTest extends TestCase
{
    use ConfigFacade;

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_elasticsearch_host()
    {
        $this->assertTrue(is_string($this->getHost()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_elasticsearch_port()
    {
        $this->assertTrue(is_int($this->getPort()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_elasticsearch_scheme()
    {
        $this->assertTrue(is_string($this->getScheme()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_elasticsearch_user()
    {
        $this->assertTrue(is_string($this->getUser()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_elasticsearch_password()
    {
        $this->assertTrue(is_string($this->getPassword()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_retries()
    {
        $this->assertTrue(is_int($this->getRetries()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_indices()
    {
        $this->assertTrue(is_array($this->getIndices()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_deploy_versions()
    {
        $this->assertTrue(is_array($this->getDeployVersions()));
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_snapshot_config()
    {
        $this->assertTrue(is_array($this->getSnapshot()));
    }
}
