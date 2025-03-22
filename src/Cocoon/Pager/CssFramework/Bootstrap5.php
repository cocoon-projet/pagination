<?php

declare(strict_types=1);

namespace Cocoon\Pager\CssFramework;

/**
 * Implémentation du framework CSS Bootstrap 5
 */
final class Bootstrap5 implements CssFrameworkInterface
{
    /**
     * Ouvre la balise de pagination
     */
    public function openTag(string $class = ''): string
    {
        $baseClass = 'pagination';
        $class = $class ? $baseClass . ' ' . $class : $baseClass;
        return sprintf('<nav aria-label="Navigation de pagination"><ul class="%s">', $class);
    }

    /**
     * Ferme la balise de pagination
     */
    public function closeTag(): string
    {
        return '</ul></nav>';
    }

    /**
     * Rend le bouton "Précédent"
     */
    public function renderPreviousButton(string $url, bool $disabled): string
    {
        $class = 'page-item';
        if ($disabled) {
            $class .= ' disabled';
        }

        if ($disabled) {
            return sprintf(
                '<li class="%s"><span class="page-link">&laquo;</span></li>',
                $class
            );
        }

        return sprintf(
            '<li class="%s"><a class="page-link" href="%s">&laquo;</a></li>',
            $class,
            $url
        );
    }

    /**
     * Rend le bouton "Suivant"
     */
    public function renderNextButton(string $url, bool $disabled): string
    {
        $class = 'page-item';
        if ($disabled) {
            $class .= ' disabled';
        }

        if ($disabled) {
            return sprintf(
                '<li class="%s"><span class="page-link">&raquo;</span></li>',
                $class
            );
        }

        return sprintf(
            '<li class="%s"><a class="page-link" href="%s">&raquo;</a></li>',
            $class,
            $url
        );
    }

    /**
     * Rend l'information de page (Page X sur Y)
     */
    public function renderPageInfo(int $currentPage, int $totalPages): string
    {
        return sprintf(
            '<li class="page-item disabled"><span class="page-link">Page %d sur %d</span></li>',
            $currentPage,
            $totalPages
        );
    }

    /**
     * Rend un lien de page
     */
    public function renderPageLink(string $url, int $page, bool $isActive = false): string
    {
        $class = 'page-item';
        if ($isActive) {
            $class .= ' active';
        }

        // Pour les ellipsis (...)
        if ($page === 0) {
            return sprintf(
                '<li class="%s"><span class="page-link">&hellip;</span></li>',
                $class
            );
        }

        if ($isActive) {
            return sprintf(
                '<li class="%s"><span class="page-link">%d</span></li>',
                $class,
                $page
            );
        }

        return sprintf(
            '<li class="%s"><a class="page-link" href="%s">%d</a></li>',
            $class,
            $url,
            $page
        );
    }
}
