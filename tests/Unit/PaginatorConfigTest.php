<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Cocoon\Pager\PaginatorConfig;

class PaginatorConfigTest extends TestCase
{
    private PaginatorConfig $config;
    private array $testData;

    protected function setUp(): void
    {
        $this->testData = array_map(fn($i) => ['id' => $i], range(1, 100));
        $this->config = new PaginatorConfig($this->testData, count($this->testData));
    }

    public function testConstructorInitializesCorrectly(): void
    {
        $this->assertSame($this->testData, $this->config->getData());
        $this->assertSame(100, $this->config->getTotal());
        $this->assertSame('page', $this->config->getForPage());
    }

    public function testSetAndGetPerPage(): void
    {
        $this->config->setPerPage(20);
        $this->assertSame(20, $this->config->getPerPage());
    }

    public function testSetAndGetStyling(): void
    {
        $this->config->setStyling('basic');
        $this->assertSame('basic', $this->config->getStyling());
    }

    public function testSetAndGetCssFramework(): void
    {
        $this->config->setCssFramework('bootstrap5');
        $this->assertSame('bootstrap5', $this->config->getCssFramework());
    }

    public function testInvalidCssFrameworkThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->config->setCssFramework('invalid');
    }

    public function testSetAndGetForPage(): void
    {
        $this->config->setForPage('p');
        $this->assertSame('p', $this->config->getForPage());
    }

    public function testFluentInterface(): void
    {
        $result = $this->config
            ->setPerPage(20)
            ->setStyling('basic')
            ->setCssFramework('bootstrap5')
            ->setForPage('p');

        $this->assertInstanceOf(PaginatorConfig::class, $result);
    }
} 