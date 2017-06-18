<?php

/**
 * Trait ScElasticsearchTestHelper
 *
 * @author Christopher Lorke <christopher.lorke@gmx.de>
 */
trait ScElasticsearchTestHelper
{
    /**
     * @return array
     */
    public function generateElasticsearchConfig(): array
    {
        return [
            'host' => 'Host',
            'port' => 9200,
            'scheme' => 'https',
            'user' => 'elastic',
            'pass' => 'changeme',
            'config' => [
                'retries' => 2,
                'indices' => []
            ]
        ];
    }

    /**
     * @return \Triadev\Es\Repository\ConfigRepository
     */
    public function generateConfigRepository() : \Triadev\Es\Repository\ConfigRepository
    {
        return new \Triadev\Es\Repository\ConfigRepository(
            $this->generateElasticsearchConfig()
        );
    }

    /**
     * @return \Triadev\Es\Contract\ScElasticsearchClientContract
     */
    public function generateScElasticsearchClient() : \Triadev\Es\Contract\ScElasticsearchClientContract
    {
        return new \Triadev\Es\ScElasticsearchClient($this->generateConfigRepository());
    }

    /**
     * @return \Mockery\MockInterface
     */
    private function buildEsClientMock() : \Mockery\MockInterface
    {
        return Mockery::mock(\Elasticsearch\Client::class);
    }

    /**
     * @return \Mockery\MockInterface
     */
    private function buildIndicesNamespaceMock() : \Mockery\MockInterface
    {
        return Mockery::mock(\Elasticsearch\Namespaces\IndicesNamespace::class);
    }
}
