<?php
namespace Tests;

use Cocoon\Pager\Paginator;
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
        $options['styling'] = 'basic';
        $p = new Paginator($collection, $options);
        $this->assertSame(16,$p->count());
    }
}
