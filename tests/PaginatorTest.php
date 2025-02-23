<?php
namespace Tests;

use Cocoon\Pager\Paginator;
use Cocoon\Pager\DataForPage;
use Cocoon\Pager\PaginatorConfig;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testInitPaginator(): void
    {
        $collection = [
            'un',
            'deux',
            'trois',
            'quatre',
            'cinq',
            'six',
            'sept',
            'huit',
            'neuf',
            'dix',
            'onze',
            'douze',
            'treize',
            'quatorze',
            'quize',
            'seize'
        ];

        $_SERVER['REQUEST_URI'] = '/';
        $items = ($count = count($collection))
        ? $collection
        : [];
        $data = new PaginatorConfig($items, $count);
        $data->setPerPage(1);
        $pager = new Paginator($data);
        $this->assertSame(16, $pager->count());
    }
}
