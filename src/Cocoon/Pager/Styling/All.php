<?php
/*
 * 
 *
 * (c) Franck Pichot <contact@cocoon-projet.fr>
 *
 * Ce fichier est sous licence MIT.
 * Consulter le fichier LICENCE du projet. LICENSE.txt.
 *
 */
namespace Cocoon\Pager\Styling;

class All
{

    protected $pager;
    protected $container;

    public function __construct($pager)
    {
        $this->pager = $pager;
    }

    public function render($class)
    {
        $html = '';
        $html .= '<ul class="pagination ' . $class . '">';
        if ($this->pager->getCurrentPage() != 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' .
                     $this->pager->getUrl() . $this->pager->getPreviousPage() . $this->pager->getAppends() .
                     '" title="Page précédente">&laquo;</a></li> ';
        }
        foreach ($this->pager->getLinksPage() as $page) {
            if ($page == $this->pager->getCurrentPage()) {
                $html .= '<li class="page-item active"><a class="page-link" href="#">' . $page . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' .
                    $this->pager->getUrl() . $page . $this->pager->getAppends() . '">' .
                    $page . '</a></li>';
            }
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
