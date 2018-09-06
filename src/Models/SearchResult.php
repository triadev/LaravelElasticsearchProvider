<?php
namespace Triadev\Es\Models;

class SearchResult
{
    /**
     * @var int
     */
    private $took;
    
    /**
     * @var int
     */
    private $timed_out;
    
    /**
     * @var Shards
     */
    private $shards;
    
    /**
     * @var int
     */
    private $total;
    
    /**
     * @var double|null
     */
    private $max_score;
    
    /**
     * @var string|null
     */
    private $scroll_id;
    
    /**
     * @var array
     */
    private $hits = [];
    
    /**
     * @var array
     */
    private $aggs = [];
    
    /**
     * @return int
     */
    public function getTook(): int
    {
        return $this->took;
    }
    
    /**
     * @param int $took
     */
    public function setTook(int $took)
    {
        $this->took = $took;
    }
    
    /**
     * @return Shards
     */
    public function getShards(): Shards
    {
        return $this->shards;
    }
    
    /**
     * @param Shards $shards
     */
    public function setShards(Shards $shards)
    {
        $this->shards = $shards;
    }
    
    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
    
    /**
     * @param int $total
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
    }
    
    /**
     * @return float|null
     */
    public function getMaxScore(): ?float
    {
        return $this->max_score;
    }
    
    /**
     * @param float|null $max_score
     */
    public function setMaxScore(?float $max_score)
    {
        $this->max_score = $max_score;
    }
    
    /**
     * @return array
     */
    public function getHits(): array
    {
        return $this->hits;
    }
    
    /**
     * @param array $hits
     */
    public function setHits(array $hits)
    {
        $this->hits = $hits;
    }
    
    /**
     * @param Hit $hit
     */
    public function addHit(Hit $hit)
    {
        $this->hits[] = $hit;
    }
    
    /**
     * @return int
     */
    public function getTimedOut(): int
    {
        return $this->timed_out;
    }
    
    /**
     * @param int $timed_out
     */
    public function setTimedOut(int $timed_out)
    {
        $this->timed_out = $timed_out;
    }
    
    /**
     * @return array
     */
    public function getAggs(): array
    {
        return $this->aggs;
    }
    
    /**
     * @param array $aggs
     */
    public function setAggs(array $aggs)
    {
        $this->aggs = $aggs;
    }
    
    /**
     * @return null|string
     */
    public function getScrollId(): ?string
    {
        return $this->scroll_id;
    }
    
    /**
     * @param null|string $scroll_id
     */
    public function setScrollId(?string $scroll_id): void
    {
        $this->scroll_id = $scroll_id;
    }
}
