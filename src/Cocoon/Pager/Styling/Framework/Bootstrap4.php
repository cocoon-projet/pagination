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
 * Implémentation du framework CSS Bootstrap 4 pour la pagination
 */
class Bootstrap4 implements CssFrameworkInterface
{
    /**
     * {@inheritDoc}
     */
    public function renderPageItem(int $page, bool $isActive, string $url): string
    {
        if ($isActive) {
            return sprintf(
                '<li class="page-item active"><a class="page-link" href="#">%d ' .
                '<span class="sr-only">(current)</span></a></li>',
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
            return '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">&laquo;</a></li>';
        }
        
        return sprintf(
            '<li class="page-item"><a class="page-link" href="%s" aria-label="Précédent">' .
            '<span aria-hidden="true">&laquo;</span>' .
            '<span class="sr-only">Précédent</span></a></li>',
            $url
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderNextItem(string $url, bool $disabled = false): string
    {
        if ($disabled) {
            return '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">&raquo;</a></li>';
        }
        
        return sprintf(
            '<li class="page-item"><a class="page-link" href="%s" aria-label="Suivant">' .
            '<span aria-hidden="true">&raquo;</span>' .
            '<span class="sr-only">Suivant</span></a></li>',
            $url
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function renderEllipsis(): string
    {
        return '<li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>';
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
