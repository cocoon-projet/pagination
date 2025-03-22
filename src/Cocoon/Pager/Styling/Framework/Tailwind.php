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
 * Implémentation du framework CSS Tailwind
 */
final class Tailwind implements CssFrameworkInterface
{
    /**
     * Ouvre la balise de pagination
     */
    public function openTag(string $class = ''): string
    {
        $baseClass = 'relative z-0 inline-flex rounded-md shadow-sm -space-x-px';
        $class = $class ? $baseClass . ' ' . $class : $baseClass;
        return sprintf('<nav class="%s">', $class);
    }

    /**
     * Ferme la balise de pagination
     */
    public function closeTag(): string
    {
        return '</nav>';
    }

    /**
     * Rend le bouton "Précédent"
     */
    public function renderPreviousButton(string $url, bool $disabled): string
    {
        $class = 'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium';
        $class .= $disabled
            ? ' text-gray-300 cursor-not-allowed'
            : ' text-gray-500 hover:bg-gray-50';

        if ($disabled) {
            return sprintf(
                '<span class="%s"><span class="sr-only">Précédent</span>&laquo;</span>',
                $class
            );
        }

        return sprintf(
            '<a href="%s" class="%s"><span class="sr-only">Précédent</span>&laquo;</a>',
            $url,
            $class
        );
    }

    /**
     * Rend le bouton "Suivant"
     */
    public function renderNextButton(string $url, bool $disabled): string
    {
        $class = 'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium';
        $class .= $disabled
            ? ' text-gray-300 cursor-not-allowed'
            : ' text-gray-500 hover:bg-gray-50';

        if ($disabled) {
            return sprintf(
                '<span class="%s"><span class="sr-only">Suivant</span>&raquo;</span>',
                $class
            );
        }

        return sprintf(
            '<a href="%s" class="%s"><span class="sr-only">Suivant</span>&raquo;</a>',
            $url,
            $class
        );
    }

    /**
     * Rend l'information de page (Page X sur Y)
     */
    public function renderPageInfo(int $currentPage, int $totalPages): string
    {
        return sprintf(
            '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">Page %d sur %d</span>',
            $currentPage,
            $totalPages
        );
    }

    /**
     * Rend un lien de page
     */
    public function renderPageLink(string $url, int $page, bool $isActive = false): string
    {
        $class = 'relative inline-flex items-center px-4 py-2 border text-sm font-medium';
        
        if ($isActive) {
            $class .= ' z-10 bg-indigo-50 border-indigo-500 text-indigo-600';
        } else {
            $class .= ' bg-white border-gray-300 text-gray-500 hover:bg-gray-50';
        }

        if ($isActive) {
            return sprintf(
                '<span aria-current="page" class="%s">%d</span>',
                $class,
                $page
            );
        }

        return sprintf(
            '<a href="%s" class="%s">%d</a>',
            $url,
            $class,
            $page
        );
    }
}
