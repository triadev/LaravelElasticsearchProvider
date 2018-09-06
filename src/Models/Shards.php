<?php
namespace Triadev\Es\Models;

class Shards
{
    /**
     * @var int
     */
    private $total = 0;
    
    /**
     * @var int
     */
    private $successful = 0;
    
    /**
     * @var int
     */
    private $failed = 0;
    
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
     * @return int
     */
    public function getSuccessful(): int
    {
        return $this->successful;
    }
    
    /**
     * @param int $successful
     */
    public function setSuccessful(int $successful)
    {
        $this->successful = $successful;
    }
    
    /**
     * @return int
     */
    public function getFailed(): int
    {
        return $this->failed;
    }
    
    /**
     * @param int $failed
     */
    public function setFailed(int $failed)
    {
        $this->failed = $failed;
    }
}
