<?php
namespace Tests\Integration\Console\Commands\Alias;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchAliasContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class DeleteTest extends IntegrationTestCase
{
    /** @var ElasticsearchIndexContract */
    private $serviceIndex;
    
    /** @var ElasticsearchAliasContract */
    private $serviceAlias;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->serviceIndex = app(ElasticsearchIndexContract::class);
        $this->serviceAlias = app(ElasticsearchAliasContract::class);
    
        $this->artisan('triadev:es:index:create', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
    
        $this->assertTrue($this->serviceIndex->existIndex([
            'phpunit'
        ], '1.0.0'));
        
        if ($this->serviceAlias->existAlias(['phpunit'], ['alias'], '1.0.0')) {
            $this->serviceAlias->deleteAlias('phpunit', 'alias', '1.0.0');
        }
    }
    
    /**
     * @test
     */
    public function it_creates_an_alias()
    {
        $this->artisan('triadev:es:alias:create', [
            'index' => 'phpunit',
            'alias' => 'alias',
            'version' => '1.0.0'
        ])->assertExitCode(0);
    
        $this->assertTrue($this->serviceAlias->existAlias(['phpunit'], ['alias'], '1.0.0'));
    
        $this->artisan('triadev:es:alias:delete', [
            'index' => 'phpunit',
            'alias' => 'alias',
            'version' => '1.0.0'
        ])->assertExitCode(0);
    
        $this->assertFalse($this->serviceAlias->existAlias(['phpunit'], ['alias'], '1.0.0'));
    }
}
