<?php
namespace Tests\Integration\Console\Commands\Index;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class CreateTest extends IntegrationTestCase
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
    public function it_creates_an_index()
    {
        $this->artisan('triadev:es:index:create', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
        
        $this->assertTrue($this->service->existIndex([
            'phpunit'
        ], '1.0.0'));
    }
}
