<?php

namespace Cocoon\Pager;

use Traversable;
use Cocoon\Pager\Pager;

class Paginator implements \IteratorAggregate
{
    protected $items;

    protected $pager;
    
    protected $total;

    public function __construct($items, $options)
    {
        $this->items = $items;
        $this->total = count($items);
        $this->pager = new Pager($this->total, $options);
    }

    public function appends($appends = [])
    {
        return $this->pager->appends($appends);
    }
    
    public function count()
    {
        return $this->total;
    }

    public function links($class = 'pagination-sm')
    {
        return $this->pager->links($class);
    }

    public function getIterator() :Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
