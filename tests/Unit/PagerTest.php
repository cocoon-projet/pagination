<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Cocoon\Pager\Pager;
use Cocoon\Pager\PagerIterator;

class PagerTest extends TestCase
{
    private Pager $pager;
    private array $testData;

    protected function setUp(): void
    {
        $this->testData = range(1, 100);
        $this->pager = new Pager(count($this->testData), [
            'perpage' => 10,
            'paging' => 'page',
            'styling' => 'all',
            'cssFramework' => 'bootstrap5'
        ]);
    }

    public function testConstructorInitializesCorrectly(): void
    {
        $this->assertSame(10, $this->pager->getPerPage());
        $this->assertSame(1, $this->pager->getCurrentPage());
        $this->assertSame(10, $this->pager->count());
    }

    public function testPaginationCalculations(): void
    {
        $this->assertSame(1, $this->pager->getFirstPage());
        $this->assertSame(10, $this->pager->getLastPage());
        $this->assertSame(0, $this->pager->getOffset());
        $this->assertTrue($this->pager->hasPages());
    }

    public function testPageNavigation(): void
    {
        $this->pager->setCurrentPage(5);
        
        $this->assertSame(5, $this->pager->getCurrentPage());
        $this->assertSame(4, $this->pager->getPreviousPage());
        $this->assertSame(6, $this->pager->getNextPage());
        $this->assertFalse($this->pager->onFirstPage());
        $this->assertFalse($this->pager->onLastPage());
    }

    public function testPageBoundaries(): void
    {
        // Test première page
        $this->pager->setCurrentPage(1);
        $this->assertTrue($this->pager->onFirstPage());
        $this->assertSame(1, $this->pager->getPreviousPage());
        
        // Test dernière page
        $this->pager->setCurrentPage(10);
        $this->assertTrue($this->pager->onLastPage());
        $this->assertSame(10, $this->pager->getNextPage());
    }

    public function testUrlGeneration(): void
    {
        $this->assertStringContainsString('page=', $this->pager->getUrl());
        
        // Test avec paramètres supplémentaires
        $this->pager->append(['sort' => 'desc']);
        $this->assertStringContainsString('sort=desc', $this->pager->getAppends());
        
        $url = $this->pager->getUrlForPage(2);
        $this->assertStringContainsString('page=2', $url);
        $this->assertStringContainsString('sort=desc', $url);
    }

    public function testIterator(): void
    {
        $iterator = $this->pager->getIterator();
        $this->assertInstanceOf(PagerIterator::class, $iterator);
        
        $pages = iterator_to_array($iterator);
        $this->assertCount(10, $pages);
        $this->assertSame(range(1, 10), $pages);
    }

    public function testDeltaConfiguration(): void
    {
        $this->pager->setDelta(2);
        $this->assertSame(2, $this->pager->getDelta());
        
        // Test avec style sliding
        $this->pager = new Pager(100, [
            'perpage' => 10,
            'styling' => 'sliding'
        ]);
        $this->pager->setDelta(2);
        $this->assertSame(5, $this->pager->getDelta()); // 2 * 2 + 1
    }

    public function testPaginationInfo(): void
    {
        $info = $this->pager->info();
        
        $this->assertArrayHasKey('currentPage', $info);
        $this->assertArrayHasKey('firstPage', $info);
        $this->assertArrayHasKey('lastPage', $info);
        $this->assertArrayHasKey('nextPage', $info);
        $this->assertArrayHasKey('numPages', $info);
        $this->assertArrayHasKey('perpage', $info);
        $this->assertArrayHasKey('previousPage', $info);
        $this->assertArrayHasKey('styling', $info);
        $this->assertArrayHasKey('url', $info);
        $this->assertArrayHasKey('cssFramework', $info);
        
        $this->assertSame(1, $info['currentPage']);
        $this->assertSame(10, $info['lastPage']);
        $this->assertSame(10, $info['perpage']);
    }

    public function testRenderWithNoPages(): void
    {
        $pager = new Pager(5, ['perpage' => 10]);
        $this->assertSame('', $pager->render());
    }
} 