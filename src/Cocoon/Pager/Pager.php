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

use Traversable;
use IteratorAggregate;

/**
 * Gestion de la la pagination des données.
 *
 * Class Pager
 * @package Cocoon\Pager
 */
class Pager implements IteratorAggregate
{
    protected $maxPerPage = 1;
    //Pour le style sliding 4*2+1
    public $delta = 4;
    protected $styling = 'all';
    protected $stylingPager = ['basic', 'all', 'elastic', 'sliding'];
    public $pages = [];
    protected $paging;
    protected $requete;
    protected $offset = 0;
    protected $currentPage = null;
    protected $countData;
    protected $url;
    protected $collection;
    protected $appends = [];

    /**
     *
     * @param int $collection nombre d'éléments
     * @param array $options key: styling, paging, perpage, delta
     * @internal param int $setCurrentpageNumber
     */
    public function __construct($collection, array $options = null)
    {
        $this->setCollection($collection);
        $this->initOptions($options);
        $this->setCountDataAndPage();
        $this->initCurrentPage($_GET);
        $this->setUrl();
    }

    /**
     * Initialise les paramètres pour la pagination
     * Nombre de donnée et nombre de page
     */
    private function setCountDataAndPage()
    {
        $this->countData = $this->requete;
        $this->setCountPage($this->requete);
    }

    private function initOptions($options = [])
    {
        if (isset($options['perpage'])) {
            $this->maxPerPage = $options['perpage'];
        }
        if (isset($options['styling'])) {
            if ($options['styling'] == 'elastic') {
                $this->setDelta(9);
            }
            $this->setStyling($options['styling']);
        }
        if (isset($options['paging'])) {
            $this->setPaging($options['paging']);
        }
        if (isset($options['delta'])) {
            $this->setDelta($options['delta']);
        }
    }

    private function setPaging($paging)
    {
        $this->paging = $paging;
    }

    private function initCurrentPage($curentPage) :void
    {
        if (isset($curentPage['page'])) {
            $page = $curentPage['page'];
        } else {
            $page = 1;
        }
        if ($page <= 0) {
            $page = 1;
        }
        if ($page > count($this->pages)) {
            $page = count($this->pages);
        }
        $this->currentPage = (int) $page;
    }

    private function setCollection($collection)
    {
        $this->requete = $collection;
    }

    /**
     *
     * @param numeric $data
     */
    private function setCountPage($data)
    {
        $arrPages = [];
        $count = ceil($data / $this->maxPerPage);
        for ($i = 1; $i <= $count; $i++) {
            $arrPages[$i - 1] = $i;
        }
        $this->pages = $arrPages;
    }

    /**
     * Paramétrage pour le lien
     *
     * @param string $url
     *
     */
    public function setUrl()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        $this->url = trim($requestUri . strtolower('?page='));
    }

    public function setDelta($delta)
    {
        $this->delta = $delta;
    }

    public function setStyling($style)
    {
        $this->styling = $style;
        if ($style == 'sliding') {
            $this->delta = $this->delta * 2 + 1;
        }
    }

    private function getStyling()
    {
        return $this->styling;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getNbResult()
    {
        return $this->countData;
    }

    public function count(): int
    {
        return count(array($this->pages));
    }

    public function getIterator() : Traversable
    {
        return new PagerIterator($this->pages);
    }

    public function getFirstPage()
    {
        return 1;
    }

    public function getLastPage()
    {
        return $this->count();
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getPreviousPage()
    {
        return $this->currentPage - 1;
    }

    public function getNextPage()
    {
        return $this->currentPage + 1;
    }

    public function getLinksPage()
    {
        return $this->getIterator();
    }


    public function appends($appends = [])
    {
        foreach ($appends as $key => $value) {
            $this->appends[] = $key . '='  . $value;
        }
        return $this;
    }

    public function getAppends()
    {
        if (count($this->appends) > 0) {
            return '&' . implode('&', $this->appends);
        }
        return '';
    }

    public function limit()
    {
        return $this->maxPerPage;
    }

    public function offset()
    {
        return $this->getCurrentPage() - 1 * $this->maxPerPage;
    }

    public function links($class = 'pagination-sm')
    {
        if ($this->getNbResult() === 0) {
            return '';
        }
        if (!in_array($this->getStyling(), $this->stylingPager)) {
            throw new \Exception('Rendu de style de pagination invalide');
        }
        $styling = $className = "Cocoon\\Pager\\Styling\\" . ucfirst($this->getStyling());
        $style = new $styling($this);
        return $style->render($class);
    }
}
