<?php
namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchIndexContract;
use Triadev\Es\Contract\ElasticsearchMappingContract;

class ElasticsearchMappingTest extends IntegrationTestCase
{
    /** @var ElasticsearchMappingContract */
    private $service;
    
    /** @var ElasticsearchIndexContract */
    private $serviceIndex;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->service = app(ElasticsearchMappingContract::class);
        $this->serviceIndex = app(ElasticsearchIndexContract::class);
    
        $this->serviceIndex->deleteAllIndices();
    
        $this->serviceIndex->createIndex('index', [
            'body' => $this->getMapping()
        ]);
    }
    
    /**
     * @test
     */
    public function it_gets_a_mapping()
    {
        $this->assertEquals(
            $this->getMapping(),
            $this->service->getMapping('index', 'phpunit')['index']
        );
    }
    
    /**
     * @test
     */
    public function it_updates_a_mapping()
    {
        $this->assertNull(array_get(
            $this->service->getMapping('index', 'phpunit'),
            'index.mappings.phpunit.properties.new_field.type'
        ));
        
        $this->service->updateMapping(
            'index',
            'phpunit',
            [
                'body' => [
                    'properties' => [
                        'new_field' => [
                            'type' => 'text'
                        ]
                    ]
                ]
            ]
        );
    
        $this->assertEquals(
            'text',
            array_get(
                $this->service->getMapping('index', 'phpunit'),
                'index.mappings.phpunit.properties.new_field.type'
            )
        );
    }
}
