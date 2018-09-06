<?php
namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class ElasticsearchIndexTest extends IntegrationTestCase
{
    /** @var ElasticsearchIndexContract */
    private $service;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->service = app(ElasticsearchIndexContract::class);
        
        $this->service->deleteAllIndexes();
    }
    
    /**
     * @test
     */
    public function it_creates_an_index()
    {
        $this->service->createIndex('phpunit', [
            'body' => $this->getMapping()
        ]);
        
        $this->assertTrue($this->service->existIndex(['phpunit']));
    
        $this->service->deleteAllIndexes();
    }
    
    /**
     * @test
     * @expectedException \Triadev\Es\Exception\Index\IndexFoundException
     */
    public function it_throws_an_exception_if_index_exist()
    {
        $this->service->createIndex('phpunit', [
            'body' => $this->getMapping()
        ]);
    
        $this->service->createIndex('phpunit', [
            'body' => $this->getMapping()
        ]);
    
        $this->service->deleteAllIndexes();
    }
    
    /**
     * @test
     */
    public function it_deletes_all_indices()
    {
        $this->service->createIndex('phpunit1', [
            'body' => $this->getMapping()
        ]);
    
        $this->service->createIndex('phpunit2', [
            'body' => $this->getMapping()
        ]);
        
        $this->assertTrue($this->service->existIndex(['phpunit1']));
        $this->assertTrue($this->service->existIndex(['phpunit2']));
        
        $this->service->deleteAllIndexes();
    
        $this->assertFalse($this->service->existIndex(['phpunit1']));
        $this->assertFalse($this->service->existIndex(['phpunit2']));
    }
    
    /**
     * @test
     */
    public function it_returns_versioned_indices()
    {
        $this->service->createIndex('phpunit_1.0.0', [
            'body' => $this->getMapping()
        ]);
    
        $this->service->createIndex('phpunit_1.0.1', [
            'body' => $this->getMapping()
        ]);
        
        $indices = $this->service->getVersionedIndices('phpunit');
        
        $this->assertTrue(in_array('phpunit_1.0.0', $indices));
        $this->assertTrue(in_array('phpunit_1.0.1', $indices));
        
        $this->service->deleteAllIndexes();
    }
}
