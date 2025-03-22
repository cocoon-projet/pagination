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
 * Style de pagination avec fenêtre glissante
 * Affiche une fenêtre de pages qui se déplace avec la page courante
 */
final class Sliding implements StylingInterface
{
    /**
     * Rend la pagination avec le style sliding
     */
    public function render(Pager $pager): string
    {
        $framework = $pager->getCssFrameworkInstance();
        $html = [];
        $delta = (int)($pager->getDelta() / 2);
        $currentPage = $pager->getCurrentPage();
        $lastPage = $pager->getLastPage();

        // Ouvre le conteneur de pagination
        $html[] = $framework->openTag();

        // Bouton précédent
        $html[] = $framework->renderPreviousButton(
            $pager->getUrlForPage($pager->getPreviousPage()),
            $pager->onFirstPage()
        );

        // Calcul de la fenêtre glissante
        $start = max(1, $currentPage - $delta);
        $end = min($lastPage, $currentPage + $delta);

        // Ajuster la fenêtre si nécessaire
        if ($start > 1) {
            $html[] = $framework->renderPageLink(
                $pager->getUrlForPage(1),
                1,
                false
            );
            if ($start > 2) {
                $html[] = $framework->renderPageLink(
                    '#',
                    0,
                    false
                );
            }
        }

        // Pages dans la fenêtre
        for ($i = $start; $i <= $end; $i++) {
            $html[] = $framework->renderPageLink(
                $pager->getUrlForPage($i),
                $i,
                $i === $currentPage
            );
        }

        // Dernière page si nécessaire
        if ($end < $lastPage) {
            if ($end < $lastPage - 1) {
                $html[] = $framework->renderPageLink(
                    '#',
                    0,
                    false
                );
            }
            $html[] = $framework->renderPageLink(
                $pager->getUrlForPage($lastPage),
                $lastPage,
                false
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
