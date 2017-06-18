<?php

/**
 * Class VersionHelperTest
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 */
class VersionHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_create_an_index_with_version()
    {
        $this->assertContains(
            'index_1.0.0',
            \Triadev\Es\Helper\VersionHelper::createIndexWithVersion('index', '1.0.0')
        );
    }

    /**
     * @test
     */
    public function it_create_an_index_without_version()
    {
        $this->assertContains(
            'index',
            \Triadev\Es\Helper\VersionHelper::createIndexWithVersion('index')
        );
    }

    /**
     * @test
     */
    public function it_create_an_index_with_null_as_version()
    {
        $this->assertContains(
            'index',
            \Triadev\Es\Helper\VersionHelper::createIndexWithVersion('index', null)
        );
    }
}
