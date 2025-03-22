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
 * Style de pagination affichant tous les numéros de page
 */
final class All implements StylingInterface
{
    /**
     * Rend la pagination avec tous les numéros de page
     */
    public function render(Pager $pager): string
    {
        $framework = $pager->getCssFrameworkInstance();
        $html = [];

        // Ouvre le conteneur de pagination
        $html[] = $framework->openTag();

        // Bouton précédent
        $html[] = $framework->renderPreviousButton(
            $pager->getUrlForPage($pager->getPreviousPage()),
            $pager->onFirstPage()
        );

        // Numéros de page
        foreach ($pager->getPages() as $page) {
            $html[] = $framework->renderPageLink(
                $pager->getUrlForPage($page),
                $page,
                $page === $pager->getCurrentPage()
            );
        }

        // Bouton suivant
        $html[] = $framework->renderNextButton(
            $pager->getUrlForPage($pager->getNextPage()),
            $pager->onLastPage()
        );

        // Ferme le conteneur de pagination
        $html[] = $framework->closeTag();

        return implode('', $html);
    }
}
