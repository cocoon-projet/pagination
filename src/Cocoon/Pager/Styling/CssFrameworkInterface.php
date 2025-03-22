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
namespace Cocoon\Pager\Styling;

use Cocoon\Pager\Pager;

/**
 * Interface CssFrameworkInterface
 *
 * Cette interface définit les méthodes requises pour un framework CSS
 * utilisé dans le système de pagination.
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
