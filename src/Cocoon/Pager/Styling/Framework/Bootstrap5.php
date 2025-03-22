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
namespace Cocoon\Pager\Styling\Framework;

use Cocoon\Pager\Styling\CssFrameworkInterface;

/**
 * Implémentation du framework CSS Bootstrap 5 pour la pagination
 */
class Bootstrap5 implements CssFrameworkInterface
{
    /**
     * {@inheritDoc}
     */
    public function renderPageItem(int $page, bool $isActive, string $url): string
    {
        if ($isActive) {
            return sprintf(
                '<li class="page-item active" aria-current="page"><span class="page-link">%d</span></li>',
                $page
            );
        }
        
        return sprintf(
            '<li class="page-item"><a class="page-link" href="%s">%d</a></li>',
            $url,
            $page
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderPreviousItem(string $url, bool $disabled = false): string
    {
        if ($disabled) {
            return '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
        }
        
        return sprintf(
            '<li class="page-item"><a class="page-link" href="%s" aria-label="Précédent">'
            . '<span aria-hidden="true">&laquo;</span></a></li>',
            $url
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderNextItem(string $url, bool $disabled = false): string
    {
        if ($disabled) {
            return '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
        }
        
        return sprintf(
            '<li class="page-item"><a class="page-link" href="%s" aria-label="Suivant">'
            . '<span aria-hidden="true">&raquo;</span></a></li>',
            $url
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderEllipsis(): string
    {
        return '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderOpeningTag(string $class = ''): string
    {
        $classes = 'pagination';
        
        if (!empty($class)) {
            $classes .= ' ' . $class;
        }
        
        return sprintf('<nav aria-label="Navigation de pagination"><ul class="%s">', $classes);
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderClosingTag(): string
    {
        return '</ul></nav>';
    }
}
