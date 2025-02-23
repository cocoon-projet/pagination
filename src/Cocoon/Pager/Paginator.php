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
use Cocoon\Collection\Collection;

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
    public function __construct(PaginatorConfig $config)
    {
        $this->items = $config->getData();
        $this->total = $config->getTotal();
        $options['perpage'] = $config->getPerPage();
        $options['paging'] = $config->getForPage();
        $options['styling'] = $config->getstyling();
        $page = $_GET[$options['paging']] ?? 1;
        if (is_array($this->items)) {
            $this->items = (new Collection($config->getData()))
            ->slice(($page - 1) * $options['perpage'], $options['perpage'])->all();
        } elseif (is_object($this->items) && $this->items instanceof \Illuminate\Database\Query\Builder) {
            $results = $config->getData()->limit($options['perpage'])->offset(($page - 1) * $options['perpage'])->get();
            if ($results instanceof \Illuminate\Support\Collection) {
                $this->items = $results->toArray();
            }
        } elseif (is_object($this->items) && $this->items instanceof \Cocoon\Database\Query\Builder) {
            $this->items = $config->getData()->limit($options['perpage'], ($page - 1) * $options['perpage'])->get();
        }
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
     * Retourne des informations sur la pagination
     */
    public function info()
    {
        return $this->pager->info();
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
