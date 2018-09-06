<?php
namespace Tests\Integration\Console\Commands\Index;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class DeleteTest extends IntegrationTestCase
{
    /** @var ElasticsearchIndexContract */
    private $service;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->service = app(ElasticsearchIndexContract::class);
    }
    
    /**
     * @test
     */
    public function it_deletes_an_index()
    {
        $this->artisan('triadev:es:index:create', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
        
        $this->assertTrue($this->service->existIndex([
            'phpunit'
        ], '1.0.0'));
        
        $this->artisan('triadev:es:index:delete', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
    
        $this->assertFalse($this->service->existIndex([
            'phpunit'
        ], '1.0.0'));
    }
}
