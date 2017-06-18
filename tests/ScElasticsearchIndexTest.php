<?php

/**
 * Class ScElasticsearchIndexTest
 *
 * @author Christopher Lorke <christopher.lorke@traum-ferienwohnungen.de>
 */
class ScElasticsearchIndexTest extends PHPUnit_Framework_TestCase
{
    use ScElasticsearchTestHelper;

    /**
     * @param \Mockery\MockInterface $mock
     * @return \Triadev\Es\Contract\ScElasticsearchIndexContract
     */
    private function buildElasticsearchIndex(
        \Mockery\MockInterface $mock
    ) : \Triadev\Es\Contract\ScElasticsearchIndexContract {
        return new \Triadev\Es\ScElasticsearchIndex($mock);
    }

    /**
     * @test
     */
    public function it_test_create_an_index()
    {
        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'exists'
        )->andReturn(false);

        $indicesNamespaceMock->shouldReceive(
            'create'
        )->andReturn([true]);

        $result = $this->buildElasticsearchIndex($esClientMock)->createIndex(
            'index',
            [],
            '1.0.0'
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_create_an_index_already_exist()
    {
        $this->expectException(\Triadev\Es\Exception\Index\IndexFoundException::class);

        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'exists'
        )->andReturn(true);

        $indicesNamespaceMock->shouldReceive(
            'create'
        )->andReturn([true]);

        $this->buildElasticsearchIndex($esClientMock)->createIndex(
            'index',
            [],
            '1.0.0'
        );
    }

    /**
     * @test
     */
    public function it_test_delete_an_index()
    {
        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'exists'
        )->andReturn(true);

        $indicesNamespaceMock->shouldReceive(
            'delete'
        )->andReturn([true]);

        $result = $this->buildElasticsearchIndex($esClientMock)->deleteIndex(
            ['index'],
            '1.0.0'
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_delete_an_index_not_found()
    {
        $this->expectException(\Triadev\Es\Exception\Index\IndexNotFoundException::class);

        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'exists'
        )->andReturn(false);

        $indicesNamespaceMock->shouldReceive(
            'delete'
        )->andReturn([true]);

        $this->buildElasticsearchIndex($esClientMock)->deleteIndex(
            ['index'],
            '1.0.0'
        );
    }

    /**
     * @test
     */
    public function it_test_delete_all_indices()
    {
        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'delete'
        )->andReturn([true]);

        $result = $this->buildElasticsearchIndex($esClientMock)->deleteAllIndexes();

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_get_versioned_indices()
    {
        $esClientMock = $this->buildEsClientMock();
        $indicesNamespaceMock = $this->buildIndicesNamespaceMock();

        $esClientMock->shouldReceive(
            'indices'
        )->andReturn($indicesNamespaceMock);

        $indicesNamespaceMock->shouldReceive(
            'get'
        )->andReturn([
            'index' => true
        ]);

        $result = $this->buildElasticsearchIndex($esClientMock)->getVersionedIndices(
            'index'
        );

        $this->assertArraySubset([0 => 'index'], $result);
    }

    /**
     * @test
     */
    public function it_test_reindex()
    {
        $esClientMock = $this->buildEsClientMock();

        $esClientMock->shouldReceive(
            'reindex'
        )->andReturn([true]);

        $result = $this->buildElasticsearchIndex($esClientMock)->reindex(
            'index',
            '0.0.0',
            '1.0.0',
            []
        );

        $this->assertArraySubset([true], $result);
    }
}
