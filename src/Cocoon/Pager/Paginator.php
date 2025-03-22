<?php
declare(strict_types=1);

/*
 *
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
use PDO;
use PDOStatement;
use Cocoon\Collection\Collection;
use Illuminate\Database\Query\Builder as LaravelBuilder;
use Cocoon\Database\Query\Builder as CocoonBuilder;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * Classe Paginator - Gestion de la pagination des données
 *
 * Cette classe s'occupe de la gestion des données à paginer et utilise
 * la classe Pager pour le rendu de la pagination. Elle supporte plusieurs
 * types de données en entrée :
 * - Tableaux PHP (array)
 * - Laravel Query Builder
 * - Cocoon ORM Query Builder
 *
 * @author Franck Pichot <contact@cocoon-projet.fr>
 */
final class Paginator implements IteratorAggregate
{
    /**
     * Données paginées pour la page courante
     *
     * @var array<mixed>
     */
    private array $items = [];

    /**
     * Instance de Pager pour le rendu de la pagination
     */
    private Pager $pager;

    /**
     * Configuration de la pagination
     */
    private PaginatorConfig $config;

    /**
     * Constructeur
     */
    public function __construct(PaginatorConfig $config)
    {
        $this->config = $config;
        $this->initializeItems();
        $this->initializePager();
    }

    /**
     * Initialise les éléments paginés selon le type de données
     */
    private function initializeItems(): void
    {
        $data = $this->config->getData();
        $options = $this->getOptions();
        $offset = $this->calculateOffset($options);

        $this->items = match (true) {
            is_array($data) => $this->paginateArray($data, $offset, $options['perpage']),
            $data instanceof LaravelBuilder => $this->paginateLaravelBuilder($data, $offset, $options['perpage']),
            $data instanceof CocoonBuilder => $this->paginateCocoonBuilder($data, $offset, $options['perpage']),
            default => []
        };
        //dumpe($this->items);
    }

    /**
     * Initialise l'instance de Pager pour le rendu
     */
    private function initializePager(): void
    {
        $options = $this->getOptions();
        
        $this->pager = new Pager(
            $this->config->getTotal(),
            [
                'perpage' => $options['perpage'],
                'paging' => $options['paging'],
                'styling' => $options['styling'],
                'cssFramework' => $this->config->getCssFramework()
            ]
        );
    }

    /**
     * Récupère les options de configuration
     *
     * @return array{perpage: int, paging: string, styling: string}
     */
    private function getOptions(): array
    {
        return [
            'perpage' => $this->config->getPerPage(),
            'paging' => $this->config->getForPage(),
            'styling' => $this->config->getStyling()
        ];
    }

    /**
     * Calcule l'offset pour la pagination
     *
     * @param array{perpage: int, paging: string, styling: string} $options
     */
    private function calculateOffset(array $options): int
    {
        $page = (int)($_GET[$options['paging']] ?? 1);
        return ($page - 1) * $options['perpage'];
    }

    /**
     * Pagine un tableau avec Collection
     *
     * @param array<mixed> $data
     * @return array<mixed>
     */
    private function paginateArray(array $data, int $offset, int $perPage): array
    {
        return array_slice($data, $offset, $perPage);
    }

    /**
     * Pagine avec Laravel Query Builder
     */
    private function paginateLaravelBuilder(LaravelBuilder $builder, int $offset, int $perPage): array
    {
        $results = $builder
            ->limit($perPage)
            ->offset($offset)
            ->get();

        return $results instanceof LaravelCollection
            ? $results->toArray()
            : (array)$results;
    }

    /**
     * Pagine avec Cocoon ORM
     */
    private function paginateCocoonBuilder(CocoonBuilder $builder, int $offset, int $perPage): array
    {
        return $builder
            ->limit($perPage, $offset)
            ->get();
    }

    /**
     * Récupère les éléments de la page courante
     *
     * @return array<mixed>
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * Vérifie s'il y a plus d'une page
     */
    public function hasPages(): bool
    {
        return $this->pager->count() > 1;
    }

    /**
     * Récupère le numéro de la page courante
     */
    public function currentPage(): int
    {
        return $this->pager->getCurrentPage();
    }

    /**
     * Ajoute des paramètres supplémentaires à l'URL
     *
     * @param array<string, mixed> $appends
     */
    public function appends(array $appends = []): self
    {
        $this->pager->append($appends);
        return $this;
    }

    /**
     * Compte le nombre total de pages
     */
    public function count(): int
    {
        return $this->pager->count();
    }

    /**
     * Affiche les liens de pagination
     *
     * @deprecated Utiliser render() à la place
     */
    public function links(string $class = ''): string
    {
        return $this->render($class);
    }

    /**
     * Rend la pagination avec une classe CSS optionnelle
     */
    public function render(string $class = ''): string
    {
        return $this->pager->render($class);
    }

    /**
     * Récupère les informations sur la pagination
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
        return $this->pager->info();
    }

    /**
     * Implémentation de IteratorAggregate
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items());
    }

    /**
     * Récupère l'instance de Pager
     */
    public function getPager(): Pager
    {
        return $this->pager;
    }
}
