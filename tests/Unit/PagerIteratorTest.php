<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Cocoon\Pager\PagerIterator;

class PagerIteratorTest extends TestCase
{
    private PagerIterator $iterator;
    private array $pages;

    protected function setUp(): void
    {
        $this->pages = range(1, 5);
        $this->iterator = new PagerIterator($this->pages);
    }

    public function testIteratorInitializesCorrectly(): void
    {
        $this->assertTrue($this->iterator->valid());
        $this->assertSame(0, $this->iterator->key());
        $this->assertSame(1, $this->iterator->current());
    }

    public function testIteratorTraversesAllPages(): void
    {
        $traversedPages = [];
        foreach ($this->iterator as $key => $page) {
            $traversedPages[] = $page;
        }

        $this->assertSame($this->pages, $traversedPages);
    }

    public function testIteratorRewinds(): void
    {
        // Avancer l'itérateur
        $this->iterator->next();
        $this->iterator->next();
        
        // Rembobiner
        $this->iterator->rewind();
        
        $this->assertSame(0, $this->iterator->key());
        $this->assertSame(1, $this->iterator->current());
    }

    public function testIteratorEndsCorrectly(): void
    {
        // Aller à la fin
        while ($this->iterator->valid()) {
            $this->iterator->next();
        }

        $this->assertFalse($this->iterator->valid());
    }

    public function testEmptyIterator(): void
    {
        $emptyIterator = new PagerIterator([]);
        $this->assertFalse($emptyIterator->valid());
    }
} 