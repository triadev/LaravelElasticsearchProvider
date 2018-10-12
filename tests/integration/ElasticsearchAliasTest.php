<?php
namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class ElasticsearchAliasTest extends IntegrationTestCase
{
    /** @var ElasticsearchAliasContract */
    private $service;
    
    public function setUp()
    {
        parent::setUp();
        
        /** @var ElasticsearchIndexContract $indexService */
        $indexService = app(ElasticsearchIndexContract::class);
        
        $indexService->deleteAllIndices();
    
        $this->artisan('triadev:es:index:create', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
    
        $this->assertTrue($indexService->existIndex(['phpunit'], '1.0.0'));
        
        $this->service = app(ElasticsearchAliasContract::class);
    }
    
    /**
     * @test
     */
    public function it_manages_an_alias()
    {
        $this->assertFalse($this->service->existAlias(['phpunit'], ['alias'], '1.0.0'));
        
        $this->service->addAlias('phpunit', 'alias', '1.0.0');
        
        $this->assertTrue($this->service->existAlias(['phpunit'], ['alias'], '1.0.0'));
    
        $this->service->deleteAlias('phpunit', 'alias', '1.0.0');
    
        $this->assertFalse($this->service->existAlias(['phpunit'], ['alias'], '1.0.0'));
    }
}
