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
     * @return \Mockery\MockInterface
     */
    private function buildEsClientMock() : \Mockery\MockInterface
    {
        return Mockery::mock(\Elasticsearch\Client::class);
    }

    /**
     * @return \Mockery\MockInterface
     */
    private function buildIndicesNamespaceMock() : \Mockery\MockInterface
    {
        return Mockery::mock(\Elasticsearch\Namespaces\IndicesNamespace::class);
    }

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
}
