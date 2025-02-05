<?php
/*
 *
 *
 * (c) Franck Pichot <contact@cocoon-projet.fr>
 *
 * Ce fichier est sous license MIT.
 * Consulter le fichier LICENSE du project. LICENSE.txt.
 *
 */
namespace Cocoon\Pager;

use Traversable;
use Cocoon\Pager\Pager;

/**
 * Class Paginator - Initialisation des données pour la pagination
 *
 * @author Cocoon-Projet
 * @copyright (c) 2025
 */
class Paginator implements \IteratorAggregate
{
    /**
     *
     * @var array
     */
    protected $items;

    /**
     *
     * @var object Pager
     */
    protected $pager;
    /**
     *
     * @var int
     */
    protected $total;

    /**
     * initialise la pagination
     * @param array $items
     * @param array $options perpage-styling-paging-delta
     */
    public function __construct($items, $options)
    {
        $this->items = $items;
        $this->total = count($items);
        $this->pager = new Pager($this->total, $options);
    }
    /**
     * ajoute une variable a l'url de pagination
     * @param array $appends
     */
    public function appends($appends = [])
    {
        return $this->pager->appends($appends);
    }
    /**
     * Compte le nombre d'element a paginer
     * @return int
     */
    public function count()
    {
        return $this->total;
    }
    /**
     * Affiche la pagination (bootstrap pagination)
     * @param mixed $class
     */
    public function links($class = 'pagination-sm')
    {
        return $this->pager->links($class);
    }
    /**
     * Retourne le nombre d'element a afficher pour une page
     * @return array
     */
    public function data(): array
    {
        return array_slice($this->items, $this->pager->offset(), $this->pager->limit());
    }
    /**
     * Retourne des informations sur la pagination
     */
    public function info()
    {
        return $this->pager->info();
    }

    public function getIterator() :Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
