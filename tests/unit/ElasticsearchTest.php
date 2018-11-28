<?php
namespace Tests\Unit;

use Elasticsearch\Client;
use Tests\TestCase;
use Triadev\Es\Contract\ElasticsearchContract;

class ElasticsearchTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_an_elasticsearch_client()
    {
        /** @var ElasticsearchContract $service */
        $service = app(ElasticsearchContract::class);
        
        $this->assertInstanceOf(
            Client::class,
            $service->getClient()
        );
    }
}
