<?php
namespace Cocoon\Pager\Styling;

class Basic
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
        $previous = '';
        // Page précédente
        if ($this->pager->getCurrentPage() != 1) {
            $previous = '<li class="page-item"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getPreviousPage() . $this->pager->getAppends() .
                '" title="Page précédente" aria-hidden="true">&larr; Précédent</a></li> ';
        }
        $next = '';
        // page suivante
        if ($this->pager->getCurrentPage() != $this->pager->count()) {
            $next = '<li class="page-item" style="margin-left: 20px;"><a class="page-link" href="' .
                $this->pager->getUrl() . $this->pager->getNextPage() . $this->pager->getAppends() .
                '" title="Page suivante">Suivant &rarr;</a></li>';
        }
        $html_end = '</ul>';
        return $html . $previous . $next . $html_end;
    }
}
