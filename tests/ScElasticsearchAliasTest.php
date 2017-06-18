<?php

/**
 * Class ScElasticsearchAliasTest
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 */
class ScElasticsearchAliasTest extends PHPUnit_Framework_TestCase
{
    use ScElasticsearchTestHelper;

    /**
     * @param \Mockery\MockInterface $mock
     * @return \Triadev\Es\Contract\ScElasticsearchAliasContract
     */
    private function buildElasticsearchAlias(
        \Mockery\MockInterface $mock
    ) : \Triadev\Es\Contract\ScElasticsearchAliasContract {
        return new \Triadev\Es\ScElasticsearchAlias($mock);
    }

    /**
     * @test
     */
    public function it_test_add_an_alias_found()
    {
        $this->expectException(\Triadev\Es\Exception\Alias\AliasFoundException::class);

        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'existsAlias'
        )->andReturn(true);

        $this->buildElasticsearchAlias($esClientMock)->addAlias(
            'index',
            'index',
            '1.0.0'
        );
    }

    /**
     * @test
     */
    public function it_test_add_an_alias()
    {
        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'existsAlias'
        )->andReturn(false);

        $indicesNamespaceMock->shouldReceive(
            'putAlias'
        )->andReturn([true]);

        $result = $this->buildElasticsearchAlias($esClientMock)->addAlias(
            'index',
            'index',
            '1.0.0'
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_delete_an_alias_not_found()
    {
        $this->expectException(\Triadev\Es\Exception\Alias\AliasNotFoundException::class);

        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'existsAlias'
        )->andReturn(false);

        $this->buildElasticsearchAlias($esClientMock)->deleteAlias(
            'index',
            'index',
            '1.0.0'
        );
    }

    /**
     * @test
     */
    public function it_test_delete_an_alias()
    {
        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'existsAlias'
        )->andReturn(true);

        $indicesNamespaceMock->shouldReceive(
            'deleteAlias'
        )->andReturn([true]);

        $result = $this->buildElasticsearchAlias($esClientMock)->deleteAlias(
            'index',
            'index',
            '1.0.0'
        );

        $this->assertArraySubset([true], $result);
    }
}
