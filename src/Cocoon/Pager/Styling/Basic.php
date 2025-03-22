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
 * Style de pagination basique avec boutons précédent/suivant et numéro de page
 */
final class Basic implements StylingInterface
{
    /**
     * Rend la pagination avec le style basique
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

        // Information de page courante
        $html[] = $framework->renderPageInfo(
            $pager->getCurrentPage(),
            $pager->getLastPage()
        );

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
