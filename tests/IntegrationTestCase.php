<?php
namespace Tests;

class IntegrationTestCase extends TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
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
                    'indices' => [
                        'from' => '0.0.0',
                        'to' => '1.0.0'
                    ]
                ]
            ],
            'snapshot' => [
                'repository' => 'default',
                'type' => 'gcs',
                'settings' => []
            ],
            'config' => [
                'retries' => 2,
                'indices' => [
                    'phpunit' => $this->getMapping()
                ]
            ]
        ]);
    }
    
    /**
     * Get mapping
     *
     * @return array
     */
    public function getMapping() : array
    {
        return [
            'mappings' => [
                'phpunit' => [
                    'dynamic' => 'strict',
                    'properties' => [
                        'title' => [
                            'type' => 'text'
                        ]
                    ]
                ]
            ]
        ];
    }
}
