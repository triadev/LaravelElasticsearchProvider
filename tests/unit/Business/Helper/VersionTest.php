<?php
namespace Tests\Unit\Business\Helper;

use Tests\TestCase;
use Triadev\Es\Business\Helper\Version;

class VersionTest extends TestCase
{
    use Version;

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_index_name()
    {
        $this->assertEquals(
            'index',
            $this->createIndexWithVersion('index', null)
        );
    }

    /**
     * @test
     * @group LaravelElasticsearch
     */
    public function it_gives_the_index_name_with_version()
    {
        $this->assertEquals(
            'index_v1',
            $this->createIndexWithVersion('index', 'v1')
        );
    }
}
