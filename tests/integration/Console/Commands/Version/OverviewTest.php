<?php
namespace Tests\Integration\Console\Commands\Version;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class OverviewTest extends IntegrationTestCase
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
    public function it_gets_all_index_versions()
    {
        $this->artisan('triadev:es:index:create', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
        
        $this->assertTrue($this->service->existIndex([
            'phpunit'
        ], '1.0.0'));
        
        $this->artisan('triadev:es:version:overview', ['index' => 'phpunit'])->assertExitCode(0);
    }
}
