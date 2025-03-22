<?php

declare(strict_types=1);

namespace Cocoon\Pager\CssFramework;

/**
 * Interface pour les frameworks CSS
 */
interface CssFrameworkInterface
{
    /**
     * Ouvre la balise de pagination
     */
    public function openTag(string $class = ''): string;

    /**
     * Ferme la balise de pagination
     */
    public function closeTag(): string;

    /**
     * Rend le bouton "Précédent"
     */
    public function renderPreviousButton(string $url, bool $disabled): string;

    /**
     * Rend le bouton "Suivant"
     */
    public function renderNextButton(string $url, bool $disabled): string;

    /**
     * Rend l'information de page (Page X sur Y)
     */
    public function renderPageInfo(int $currentPage, int $totalPages): string;

    /**
     * Rend un lien de page
     */
    public function renderPageLink(string $url, int $page, bool $isActive = false): string;
}
