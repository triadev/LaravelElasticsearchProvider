<?php
namespace Triadev\Es\Models;

class Hit
{
    /**
     * @var string
     */
    private $index;
    
    /**
     * @var string
     */
    private $type;
    
    /**
     * @var string
     */
    private $id;
    
    /**
     * @var double|null
     */
    private $score;
    
    /**
     * @var array
     */
    private $source;
    
    /** @var array|null */
    private $inner_hits;
    
    /** @var string|null */
    private $routing;
    
    /** @var string|null */
    private $parent;
    
    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }
    
    /**
     * @param string $index
     */
    public function setIndex(string $index)
    {
        $this->index = $index;
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }
    
    /**
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->score;
    }
    
    /**
     * @param float|null $score
     */
    public function setScore(?float $score)
    {
        $this->score = $score;
    }
    
    /**
     * @return array
     */
    public function getSource(): array
    {
        return $this->source;
    }
    
    /**
     * @param array $source
     */
    public function setSource(array $source)
    {
        $this->source = $source;
    }
    
    /**
     * @return array|null
     */
    public function getInnerHits(): ?array
    {
        return $this->inner_hits;
    }
    
    /**
     * @param array|null $inner_hits
     */
    public function setInnerHits(?array $inner_hits): void
    {
        $this->inner_hits = $inner_hits;
    }
    
    /**
     * @return null|string
     */
    public function getRouting(): ?string
    {
        return $this->routing;
    }
    
    /**
     * @param null|string $routing
     */
    public function setRouting(?string $routing): void
    {
        $this->routing = $routing;
    }
    
    /**
     * @return null|string
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }
    
    /**
     * @param null|string $parent
     */
    public function setParent(?string $parent): void
    {
        $this->parent = $parent;
    }
}
