<?php
namespace Tests\Integration;

use Tests\IntegrationTestCase;
use Triadev\Es\Contract\ElasticsearchDocumentContract;
use Triadev\Es\Contract\ElasticsearchIndexContract;

class ElasticsearchDocumentTest extends IntegrationTestCase
{
    /** @var ElasticsearchDocumentContract */
    private $service;
    
    public function setUp()
    {
        parent::setUp();
        
        /** @var ElasticsearchIndexContract $indexService */
        $indexService = app(ElasticsearchIndexContract::class);
        
        $indexService->deleteAllIndexes();
    
        $this->artisan('triadev:es:index:create', [
            'index' => 'phpunit',
            'version' => '1.0.0'
        ]);
    
        $this->assertTrue($indexService->existIndex(['phpunit'], '1.0.0'));
        
        $this->service = app(ElasticsearchDocumentContract::class);
    }
    
    /**
     * @test
     */
    public function it_creates_a_document()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test');
        
        sleep(1);
    
        $this->assertEquals(
            1,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
        
        $this->assertEquals(
            'Title',
            $this->service->getDocument(
                'phpunit',
                'phpunit',
                'test',
                '1.0.0'
            )['_source']['title']
        );
    }
    
    /**
     * @test
     */
    public function it_updates_a_document()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test');
    
        sleep(1);
    
        $this->service->updateDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'doc' => [
                    'title' => 'TitleUpdate'
                ]
            ]
        ], 'test');
    
        sleep(1);
    
        $this->assertEquals(
            1,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
        
        $this->assertEquals(
            'TitleUpdate',
            $this->service->getDocument(
                'phpunit',
                'phpunit',
                'test',
                '1.0.0'
            )['_source']['title']
        );
    }
    
    /**
     * @test
     */
    public function it_deletes_a_document()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test');
        
        sleep(1);
        
        $this->assertEquals(
            1,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
    
        $this->service->deleteDocument('phpunit', 'phpunit', 'test', '1.0.0');
    
        sleep(1);
    
        $this->assertEquals(
            0,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
    }
    
    /**
     * @test
     */
    public function it_deletes_documents_with_bulk()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test1');
    
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test2');
        
        sleep(1);
        
        $this->assertEquals(
            2,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
        
        $this->service->deleteDocumentsWithBulk('phpunit', 'phpunit', ['test1', 'test2'], '1.0.0');
    
        sleep(1);
    
        $this->assertEquals(
            0,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
    }
    
    /**
     * @test
     */
    public function it_deletes_documents_by_query()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test1');
    
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test2');
    
        sleep(1);
    
        $this->assertEquals(
            2,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
        
        $result = $this->service->deleteDocumentsByQuery('phpunit', 'phpunit', [
            'query' => [
                'match' => [
                    'title' => 'Title'
                ]
            ]
        ], '1.0.0', [
            'refresh' => true
        ]);
        
        $this->assertEquals(2, $result['deleted']);
    }
    
    /**
     * @test
     */
    public function it_gets_a_document()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test');
        
        sleep(1);
        
        $this->assertEquals(
            'Title',
            $this->service->getDocument(
                'phpunit',
                'phpunit',
                'test',
                '1.0.0'
            )['_source']['title']
        );
    }
    
    /**
     * @test
     */
    public function it_gets_documents_with_mget()
    {
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title1'
            ]
        ], 'test1');
    
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title2'
            ]
        ], 'test2');
        
        sleep(1);
        
        $result = $this->service->mgetDocuments(
            'phpunit',
            'phpunit',
            [
                'body' => [
                    'ids' => [
                        'test1',
                        'test2'
                    ]
                ]
            ],
            '1.0.0'
        );
        
        $this->assertCount(2, $result['docs']);
    
        $this->assertEquals(
            'Title1',
            $result['docs'][0]['_source']['title']
        );
    
        $this->assertEquals(
            'Title2',
            $result['docs'][1]['_source']['title']
        );
    }
    
    /**
     * @test
     */
    public function it_checks_if_a_document_exist()
    {
        $this->assertFalse(
            $this->service->existDocument(
                'phpunit',
                'phpunit',
                'test',
                [],
                '1.0.0'
            )
        );
        
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test');
        
        sleep(1);
    
        $this->assertTrue(
            $this->service->existDocument(
                'phpunit',
                'phpunit',
                'test',
                [],
                '1.0.0'
            )
        );
    }
    
    /**
     * @test
     */
    public function it_counts_documents()
    {
        $this->assertEquals(
            0,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
        
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test1');
        
        sleep(1);
    
        $this->assertEquals(
            1,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
    
        $this->service->createDocument('phpunit', 'phpunit', '1.0.0', [
            'body' => [
                'title' => 'Title'
            ]
        ], 'test2');
    
        sleep(1);
    
        $this->assertEquals(
            2,
            $this->service->countDocuments('phpunit', 'phpunit', [], '1.0.0')
        );
    }
}
