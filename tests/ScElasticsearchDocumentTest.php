<?php

/**
 * Class ScElasticsearchDocumentTest
 *
 * @author Christopher Lorke <christopher.lorke@traum-ferienwohnungen.de>
 */
class ScElasticsearchDocumentTest extends PHPUnit_Framework_TestCase
{
    use ScElasticsearchTestHelper;

    /**
     * @param \Mockery\MockInterface $mock
     * @return \Triadev\Es\Contract\ScElasticsearchDocumentContract
     */
    private function buildElasticsearchDocument(
        \Mockery\MockInterface $mock
    ) : \Triadev\Es\Contract\ScElasticsearchDocumentContract {
        return new \Triadev\Es\ScElasticsearchDocument($mock);
    }

    /**
     * @test
     */
    public function it_test_create_a_document()
    {
        $esClientMock = $this->buildEsClientMock();

        $esClientMock->shouldReceive(
            'index'
        )->andReturn([true]);

        $result = $this->buildElasticsearchDocument($esClientMock)->createDocument(
            'index',
            'type',
            '1.0.0',
            [],
            1
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_delete_a_document()
    {
        $esClientMock = $this->buildEsClientMock();

        $esClientMock->shouldReceive(
            'delete'
        )->andReturn([true]);

        $result = $this->buildElasticsearchDocument($esClientMock)->deleteDocument(
            'index',
            'type',
            1,
            '1.0.0',
            []
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_get_a_document()
    {
        $esClientMock = $this->buildEsClientMock();

        $esClientMock->shouldReceive(
            'get'
        )->andReturn([true]);

        $result = $this->buildElasticsearchDocument($esClientMock)->getDocument(
            'index',
            'type',
            1,
            '1.0.0'
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_mget_documents()
    {
        $esClientMock = $this->buildEsClientMock();

        $esClientMock->shouldReceive(
            'mget'
        )->andReturn([true]);

        $result = $this->buildElasticsearchDocument($esClientMock)->mgetDocuments(
            'index',
            'type',
            [],
            '1.0.0'
        );

        $this->assertArraySubset([true], $result);
    }

    /**
     * @test
     */
    public function it_test_exist_document()
    {
        $esClientMock = $this->buildEsClientMock();

        $esClientMock->shouldReceive(
            'exists'
        )->andReturn(true);

        $result = $this->buildElasticsearchDocument($esClientMock)->existDocument(
            'index',
            'type',
            1,
            [],
            '1.0.0'
        );

        $this->assertTrue($result);
    }
}
