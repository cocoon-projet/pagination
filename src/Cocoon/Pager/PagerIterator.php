<?php
/*
 * LICENCE
 *
 * (c) Franck Pichot <contact@cocoon-projet.fr>
 *
 * Ce fichier est sous licence MIT.
 * Consulter le fichier LICENCE du projet. LICENSE.txt.
 *
 */
namespace Cocoon\Pager;

use Iterator;

class PagerIterator implements Iterator
{
    private $position = 0;
    protected $array = [];

    public function __construct($array)
    {
        if (is_array($array)) {
            $this->array = $array;
        }
        $this->position = 0;
    }

    public function rewind() :void
    {
        $this->position = 0;
    }

    public function current() :mixed
    {
        return $this->array[$this->position];
    }

    public function key() :int
    {
        return $this->position;
    }

    public function next() :void
    {
        ++$this->position;
    }

    public function valid() :bool
    {
        return isset($this->array[$this->position]);
    }
}
