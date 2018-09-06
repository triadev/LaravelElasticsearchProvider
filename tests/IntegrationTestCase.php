<?php
namespace Tests;

class IntegrationTestCase extends TestCase
{
    /** @var array */
    private $mapping;
    
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->mapping = include __DIR__ . '/integration/mapping.php';
    }
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('triadev-elasticsearch', [
            'host' => 'localhost',
            'port' => 9200,
            'scheme' => 'http',
            'user' => '',
            'pass' => '',
            'deploy' => [
                'version' => [
                    'indices' => []
                ]
            ],
            'snapshot' => [
                'repository' => 'default',
                'type' => 'gcs',
                'settings' => []
            ],
            'config' => [
                'retries' => 2,
                'indices' => []
            ]
        ]);
    }
    
    protected function getMapping() : array
    {
        return $this->mapping;
    }
}
