<?php

declare(strict_types=1);

/*
 * LICENSE
 *
 * (c) Franck Pichot <contact@cocoon-projet.fr>
 *
 * Ce fichier est sous license MIT.
 * Consulter le fichier LICENSE du project. LICENSE.txt.
 *
 */
namespace Cocoon\Pager;

use Iterator;

/**
 * Itérateur pour la pagination
 *
 * Cette classe permet d'itérer sur les numéros de pages
 * disponibles dans la pagination.
 *
 * @author Franck Pichot <contact@cocoon-projet.fr>
 */
final class PagerIterator implements Iterator
{
    /**
     * Position courante dans l'itération
     */
    private int $position = 0;

    /**
     * Tableau des numéros de pages
     *
     * @var array<int>
     */
    private array $pages;

    /**
     * Constructeur
     *
     * @param array<int> $pages Tableau des numéros de pages
     */
    public function __construct(array $pages = [])
    {
        $this->pages = $pages;
        $this->rewind();
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): int
    {
        return $this->pages[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->pages[$this->position]);
    }
}
