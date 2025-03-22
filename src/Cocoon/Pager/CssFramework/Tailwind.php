<?php

declare(strict_types=1);

namespace Cocoon\Pager\CssFramework;

/**
 * Implémentation du framework CSS Tailwind
 */
final class Tailwind implements CssFrameworkInterface
{
    /**
     * Classes CSS de base pour les liens de pagination
     */
    private const BASE_LINK_CLASSES = 'relative inline-flex items-center px-4 py-2 text-sm font-semibold';
    
    /**
     * Classes CSS pour les liens actifs
     */
    private const ACTIVE_LINK_CLASSES = 'z-10 bg-indigo-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600';
    
    /**
     * Classes CSS pour les liens inactifs
     */
    private const INACTIVE_LINK_CLASSES = 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0';
    
    /**
     * Classes CSS pour les liens désactivés
     */
    private const DISABLED_LINK_CLASSES = 'text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-offset-0 cursor-not-allowed opacity-50';

    /**
     * Ouvre la balise de pagination
     */
    public function openTag(string $class = ''): string
    {
        $baseClass = 'isolate inline-flex -space-x-px rounded-md shadow-sm';
        $class = $class ? $baseClass . ' ' . $class : $baseClass;
        return sprintf('<nav class="flex items-center justify-between" aria-label="Navigation de pagination"><div class="%s">', $class);
    }

    /**
     * Ferme la balise de pagination
     */
    public function closeTag(): string
    {
        return '</div></nav>';
    }

    /**
     * Rend le bouton "Précédent"
     */
    public function renderPreviousButton(string $url, bool $disabled): string
    {
        $classes = self::BASE_LINK_CLASSES . ' rounded-l-md ';
        $classes .= $disabled ? self::DISABLED_LINK_CLASSES : self::INACTIVE_LINK_CLASSES;

        if ($disabled) {
            return sprintf(
                '<span class="%s">&laquo;</span>',
                $classes
            );
        }

        return sprintf(
            '<a href="%s" class="%s">&laquo;</a>',
            $url,
            $classes
        );
    }

    /**
     * Rend le bouton "Suivant"
     */
    public function renderNextButton(string $url, bool $disabled): string
    {
        $classes = self::BASE_LINK_CLASSES . ' rounded-r-md ';
        $classes .= $disabled ? self::DISABLED_LINK_CLASSES : self::INACTIVE_LINK_CLASSES;

        if ($disabled) {
            return sprintf(
                '<span class="%s">&raquo;</span>',
                $classes
            );
        }

        return sprintf(
            '<a href="%s" class="%s">&raquo;</a>',
            $url,
            $classes
        );
    }

    /**
     * Rend l'information de page (Page X sur Y)
     */
    public function renderPageInfo(int $currentPage, int $totalPages): string
    {
        $classes = self::BASE_LINK_CLASSES . ' ' . self::DISABLED_LINK_CLASSES;
        return sprintf(
            '<span class="%s">Page %d sur %d</span>',
            $classes,
            $currentPage,
            $totalPages
        );
    }

    /**
     * Rend un lien de page
     */
    public function renderPageLink(string $url, int $page, bool $isActive = false): string
    {
        $classes = self::BASE_LINK_CLASSES . ' ';
        
        // Pour les ellipsis (...)
        if ($page === 0) {
            return sprintf(
                '<span class="%s">&hellip;</span>',
                $classes . self::DISABLED_LINK_CLASSES
            );
        }

        $classes .= $isActive ? self::ACTIVE_LINK_CLASSES : self::INACTIVE_LINK_CLASSES;

        if ($isActive) {
            return sprintf(
                '<span class="%s">%d</span>',
                $classes,
                $page
            );
        }

        return sprintf(
            '<a href="%s" class="%s">%d</a>',
            $url,
            $classes,
            $page
        );
    }
}
