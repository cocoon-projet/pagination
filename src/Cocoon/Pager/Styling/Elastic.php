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
namespace Cocoon\Pager\Styling;

class Elastic
{

    protected $pager;
    public function __construct($pager)
    {
        $this->pager = $pager;
    }

    public function render($class)
    {
        $endPrintPages = array_slice(
            $this->pager->pages,
            $this->pager->count() - $this->pager->delta,
            $this->pager->count()
        );
        if ($this->pager->getCurrentPage() < $this->pager->delta) {
            $pages = array_slice($this->pager->pages, 0, $this->pager->delta);
        } elseif (in_array($this->pager->getCurrentPage(), $endPrintPages)) {
            $pages = $endPrintPages;
        } else {
            $pages = array_slice($this->pager->pages, $this->pager->getCurrentPage() - 2, $this->pager->delta);
        }
        $html = '';
        $html .= '<ul class="pagination ' . $class . '">';
        if ($this->pager->getCurrentPage() != 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
                      $this->pager->getUrl() . $this->pager->getPreviousPage() . $this->pager->getAppends() .
                      '" title="Page précédente" >&laquo;</a></li>';
        }
        $printOne = $this->pager->delta - 1;
        if ($this->pager->getCurrentPage() > $printOne) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getFirstPage() . $this->pager->getAppends() .
                '">' . $this->pager->getFirstPage() .
                '</a></li><li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
        foreach ($pages as $page) {
            if ($page == $this->pager->getCurrentPage()) {
                $html .= '<li class="page-item active"><a class="page-link" href="#">' . $page . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' .
                    $this->pager->getUrl() . $page . $this->pager->getAppends() .
                    '">' . $page . '</a></li>';
            }
        }
        if ($this->pager->getCurrentPage() != $this->pager->getLastPage()
            && !in_array($this->pager->getLastPage(), $pages)) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                  <li class="page-item"><a class="page-link" href="' .
                  $this->pager->getUrl() . $this->pager->getLastPage() . $this->pager->getAppends() .
                  '">' . $this->pager->getLastPage() . '</a></li>';
        }
        if ($this->pager->getCurrentPage() != $this->pager->count()) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getNextPage() . $this->pager->getAppends() .
                 '" title="Page suivante">&raquo;</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
