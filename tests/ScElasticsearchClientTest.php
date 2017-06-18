<?php

/**
 * Class ScElasticsearchClientTest
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 */
class ScElasticsearchClientTest extends PHPUnit_Framework_TestCase
{
    use ScElasticsearchTestHelper;

    /**
     * @test
     */
    public function it_create_an_elasticsearch_client()
    {
        $this->assertInstanceOf(
            \Elasticsearch\Client::class,
            $this->generateScElasticsearchClient()->getEsClient()
        );
    }
}
