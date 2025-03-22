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

namespace Cocoon\Pager;

use Traversable;
use IteratorAggregate;
use Countable;
use Cocoon\Pager\Styling\AbstractStyle;
use Cocoon\Pager\Styling\All;
use Cocoon\Pager\Styling\Basic;
use Cocoon\Pager\Styling\Elastic;
use Cocoon\Pager\Styling\Sliding;
use Cocoon\Pager\Styling\StylingInterface;
use Cocoon\Pager\CssFramework\CssFrameworkInterface;
use Cocoon\Pager\CssFramework\CssFrameworkFactory;

/**
 * Gestion de la pagination des données.
 *
 * Cette classe permet de gérer la pagination de données avec différents styles
 * et frameworks CSS.
 *
 * @author Franck Pichot <contact@cocoon-projet.fr>
 */
final class Pager implements IteratorAggregate, Countable
{
    /**
     * Styles de pagination disponibles
     */
    private const AVAILABLE_STYLES = [
        'all' => All::class,
        'basic' => Basic::class,
        'elastic' => Elastic::class,
        'sliding' => Sliding::class
    ];

    /**
     * Configuration de la pagination
     */
    private PaginatorConfig $config;

    /**
     * URL de base pour la pagination
     */
    private string $url = '';

    /**
     * Paramètres supplémentaires pour l'URL
     *
     * @var array<string>
     */
    private array $appends = [];

    /**
     * Page courante
     */
    private int $currentPage = 1;

    /**
     * Nombre total de pages
     */
    private int $lastPage;

    /**
     * Collection des données à paginer
     */
    private mixed $collection;

    /**
     * Récupère le numéro de la première page
     */
    public function getFirstPage(): int
    {
        return 1;
    }

    /**
     * Récupère le nombre total d'éléments
     */
    public function getNbResult(): int
    {
        return $this->config->getTotal();
    }

    /**
     * Récupère le delta pour les styles de pagination
     */
    private int $delta = 4;

    /**
     * Modifie le delta pour les styles de pagination
     */
    public function setDelta(int $delta): self
    {
        $this->delta = $delta;
        
        if ($this->config->getStyling() === 'sliding') {
            $this->delta = $this->delta * 2 + 1;
        }
        
        return $this;
    }

    /**
     * Récupère le delta actuel
     */
    public function getDelta(): int
    {
        return $this->delta;
    }

    /**
     * Récupère l'itérateur des pages
     */
    public function getLinksPage(): Traversable
    {
        return $this->getIterator();
    }

    /**
     * Récupère le tableau des pages
     *
     * @return array<int>
     */
    public function getPages(): array
    {
        return range(1, $this->lastPage);
    }

    /**
     * Informations sur la pagination
     *
     * @return array{
     *     currentPage: int,
     *     firstPage: int,
     *     lastPage: int,
     *     nextPage: int,
     *     numPages: int,
     *     perpage: int,
     *     previousPage: int,
     *     styling: string,
     *     url: string,
     *     cssFramework: string
     * }
     */
    public function info(): array
    {
        return [
            "perpage" => $this->getPerPage(),
            "numPages" => $this->count(),
            "styling" => $this->config->getStyling(),
            "url" => $this->getUrl(),
            "previousPage" => $this->getPreviousPage(),
            "nextPage" => $this->getNextPage(),
            "currentPage" => $this->getCurrentPage(),
            "lastPage" => $this->getLastPage(),
            "firstPage" => $this->getFirstPage(),
            "cssFramework" => $this->getCssFramework()
        ];
    }

    /**
     * Constructeur
     *
     * @param array<mixed>|int $collection Nombre d'éléments ou collection à paginer
     * @param array{
     *     styling?: string,
     *     paging?: string,
     *     perpage?: int,
     *     delta?: int,
     *     cssFramework?: string
     * }|null $options Options de configuration
     */
    public function __construct(array|int $collection, ?array $options = null)
    {
        $this->collection = $collection;
        $total = is_array($collection) ? count($collection) : $collection;
        
        $this->config = new PaginatorConfig(
            $collection,
            $total,
            $options['paging'] ?? 'page'
        );

        if ($options !== null) {
            $this->initializeFromOptions($options);
        }

        $this->lastPage = (int) ceil($total / $this->config->getPerPage());
        $this->initializeCurrentPage();
        $this->initializeUrl();
    }

    /**
     * Initialise la configuration à partir des options
     *
     * @param array{
     *     styling?: string,
     *     perpage?: int,
     *     delta?: int,
     *     cssFramework?: string
     * } $options
     */
    private function initializeFromOptions(array $options): void
    {
        if (isset($options['perpage'])) {
            $this->config->setPerPage((int) $options['perpage']);
        }

        if (isset($options['styling'])) {
            $this->config->setStyling($options['styling']);
        }

        if (isset($options['cssFramework'])) {
            $this->config->setCssFramework($options['cssFramework']);
        }
    }

    /**
     * Initialise la page courante à partir des paramètres GET
     */
    private function initializeCurrentPage(): void
    {
        $page = (int) ($_GET[$this->config->getForPage()] ?? 1);
        $this->setCurrentPage($page);
    }

    /**
     * Initialise l'URL de base pour la pagination
     */
    private function initializeUrl(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        
        $this->url = trim($requestUri . '?' . $this->config->getForPage() . '=');
    }

    /**
     * Définit la page courante
     */
    public function setCurrentPage(int $page): self
    {
        $this->currentPage = max(1, min($page, $this->lastPage));
        return $this;
    }

    /**
     * Récupère la page courante
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Récupère le nombre total de pages
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /**
     * Vérifie si la pagination a plusieurs pages
     */
    public function hasPages(): bool
    {
        return $this->lastPage > 1;
    }

    /**
     * Vérifie si la page courante est la première
     */
    public function onFirstPage(): bool
    {
        return $this->currentPage === 1;
    }

    /**
     * Vérifie si la page courante est la dernière
     */
    public function onLastPage(): bool
    {
        return $this->currentPage === $this->lastPage;
    }

    /**
     * Ajoute des paramètres supplémentaires à l'URL
     *
     * @param array<string, mixed> $appends
     */
    public function append(array $appends): self
    {
        foreach ($appends as $key => $value) {
            $this->appends[] = $key . '=' . $value;
        }
        
        return $this;
    }

    /**
     * Récupère les paramètres supplémentaires de l'URL
     */
    public function getAppends(): string
    {
        return $this->appends ? '&' . implode('&', $this->appends) : '';
    }

    /**
     * Récupère l'URL de base
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Récupère le nombre d'éléments par page
     */
    public function getPerPage(): int
    {
        return $this->config->getPerPage();
    }

    /**
     * Récupère l'offset pour la page courante
     */
    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->config->getPerPage();
    }

    /**
     * Récupère le style de pagination configuré
     */
    private function getStyle(): StylingInterface
    {
        $styleClass = self::AVAILABLE_STYLES[$this->config->getStyling()]
            ?? All::class;
        return new $styleClass();
    }

    /**
     * Récupère le nom du framework CSS utilisé
     */
    public function getCssFramework(): string
    {
        return $this->config->getCssFramework();
    }

    /**
     * Récupère l'instance du framework CSS
     */
    public function getCssFrameworkInstance(): CssFrameworkInterface
    {
        $framework = $this->getCssFramework();
        $factory = new CssFrameworkFactory();
        return $factory->create($framework);
    }

    /**
     * Rend la pagination avec une classe CSS optionnelle
     */
    public function render(string $class = ''): string
    {
        if (!$this->hasPages()) {
            return '';
        }

        $style = $this->getStyle();
        return $style->render($this);
    }

    /**
     * Implémente l'interface IteratorAggregate
     */
    public function getIterator(): Traversable
    {
        return new PagerIterator(range(1, $this->lastPage));
    }

    /**
     * Récupère la configuration de la pagination
     */
    public function getConfig(): PaginatorConfig
    {
        return $this->config;
    }

    /**
     * Récupère le nombre total de pages
     */
    public function count(): int
    {
        return $this->lastPage;
    }

    /**
     * Récupère le numéro de la page précédente
     */
    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    /**
     * Récupère le numéro de la page suivante
     */
    public function getNextPage(): int
    {
        return min($this->lastPage, $this->currentPage + 1);
    }

    /**
     * Construit l'URL pour une page donnée
     */
    public function getUrlForPage(int $page): string
    {
        return $this->url . $page . $this->getAppends();
    }
}
