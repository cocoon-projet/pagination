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

class Sliding
{

    protected $pager;

    public function __construct($pager)
    {
        $this->pager = $pager;
    }

    public function render($class)
    {
        $html = '';

        //$page_prev = $this->pager->getPreviousPage();
        //$page_next = $this->pager->getNextPage();
        $html .= '<ul class="pagination ' . $class . '">';
        if ($this->pager->getCurrentPage() != 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
            $this->pager->getUrl() . $this->pager->getPreviousPage() . $this->pager->getAppends() .
            '">&laquo;</a></li>';
        }

        $page_count = $this->pager->count();
        $number_paging = $this->pager->delta;
        $page_padding = floor($number_paging / 2);

        if ($page_count > $number_paging) {
            if ($this->pager->getCurrentPage() >= ($page_padding + 1)) {
                if ($this->pager->getCurrentPage() > ($page_count - $page_padding)) {
                    $page_start = $page_count - ($page_padding * 2);
                    $page_end = $page_count;
                } else {
                    $page_start = $this->pager->getCurrentPage() - $page_padding;
                    $page_end = $this->pager->getCurrentPage() + $page_padding;
                }
            } else {
                $page_start = 1;
                $page_end = ($page_padding * 2) + 1;
            }
        } else {
            $page_start = 1;
            $page_end = $page_count;
        }
        if ($this->pager->getCurrentPage() >= $number_paging) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getFirstPage() . $this->pager->getAppends() .
                '">' . $this->pager->getFirstPage() .
                '</a></li><li class=" page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
        $pages = [];
        for ($t = $page_start; $t <= $page_end; $t++) {
            $pages[] = $t;
            $url = $this->pager->getUrl() . $t . $this->pager->getAppends();
            if ($this->pager->getCurrentPage() == $t) {
                $html .= '<li class="page-item active">';
                $html .= '<a class="page-link" href="#">' . $t . '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $url . '">' . $t . '</a></li>';
            }
        }
        if ($this->pager->getCurrentPage() != $this->pager->getLastPage() &&
            !in_array($this->pager->getLastPage(), $pages)) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>'
                    . '<li class="page-item"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getLastPage() . $this->pager->getAppends() . '">' .
                $this->pager->getLastPage() . '</a></li>';
        }
        if ($this->pager->getCurrentPage() != $page_count) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getNextPage() . $this->pager->getAppends()
                . '">&raquo;</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
