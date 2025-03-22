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
 * Style de pagination élastique
 * Affiche un nombre variable de pages autour de la page courante
 */
final class Elastic implements StylingInterface
{
    /**
     * Rend la pagination avec le style élastique
     */
    public function render(Pager $pager): string
    {
        $framework = $pager->getCssFrameworkInstance();
        $html = [];
        $delta = $pager->getDelta();
        $currentPage = $pager->getCurrentPage();
        $lastPage = $pager->getLastPage();

        // Ouvre le conteneur de pagination
        $html[] = $framework->openTag();

        // Bouton précédent
        $html[] = $framework->renderPreviousButton(
            $pager->getUrlForPage($pager->getPreviousPage()),
            $pager->onFirstPage()
        );

        // Première page
        if ($currentPage > ($delta + 1)) {
            $html[] = $framework->renderPageLink(
                $pager->getUrlForPage(1),
                1,
                false
            );
            
            if ($currentPage > ($delta + 2)) {
                $html[] = $framework->renderPageLink(
                    '#',
                    0,
                    false
                );
            }
        }

        // Pages autour de la page courante
        for ($i = max(1, $currentPage - $delta); $i <= min($lastPage, $currentPage + $delta); $i++) {
            $html[] = $framework->renderPageLink(
                $pager->getUrlForPage($i),
                $i,
                $i === $currentPage
            );
        }

        // Dernière page
        if ($currentPage < ($lastPage - $delta)) {
            if ($currentPage < ($lastPage - $delta - 1)) {
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
