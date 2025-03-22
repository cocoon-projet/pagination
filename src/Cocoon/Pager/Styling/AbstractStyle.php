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
use Cocoon\Pager\Styling\Framework\CssFrameworkFactory;

/**
 * Classe abstraite pour les styles de pagination
 */
abstract class AbstractStyle
{
    /**
     * Instance du paginateur
     */
    protected Pager $pager;
    
    /**
     * Instance du framework CSS
     */
    protected CssFrameworkInterface $cssFramework;
    
    /**
     * Constructeur
     *
     * @param Pager $pager
     */
    public function __construct(Pager $pager)
    {
        $this->pager = $pager;
        $this->cssFramework = CssFrameworkFactory::create($pager->getCssFramework());
    }
    
    /**
     * Rendu de la pagination
     *
     * @param string $class Classes CSS supplémentaires
     * @return string
     */
    abstract public function render(string $class = ''): string;
    
    /**
     * Construit l'URL complète pour une page spécifique
     *
     * @param int $page
     * @return string
     */
    protected function buildUrl(int $page): string
    {
        return $this->pager->getUrl() . $page . $this->pager->getAppends();
    }
}
