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

/**
 * Configuration pour le système de pagination.
 *
 * Cette classe permet de centraliser les paramètres de configuration
 * pour la pagination et de les transmettre au Paginator.
 *
 * @author Franck Pichot <contact@cocoon-projet.fr>
 */
final class PaginatorConfig
{
    /**
     * Frameworks CSS supportés
     */
    private const SUPPORTED_FRAMEWORKS = ['bootstrap4', 'bootstrap5', 'tailwind'];

    /**
     * Les données à paginer
     */
    private mixed $data;
    
    /**
     * Nombre d'éléments par page
     */
    private int $perPage = 10;
    
    /**
     * Nom du paramètre dans l'URL pour la page courante
     */
    private string $forPage;
    
    /**
     * Style de pagination (all, basic, elastic, sliding)
     */
    private string $styling = 'all';
    
    /**
     * Nombre total d'éléments à paginer
     */
    private int $total;
    
    /**
     * Framework CSS à utiliser pour le rendu
     */
    private string $cssFramework = 'bootstrap5';

    /**
     * Constructeur de la configuration de pagination
     */
    public function __construct(
        mixed $data,
        int $total,
        string $forPage = 'page'
    ) {
        $this->setData($data)
            ->setTotal($total)
            ->setForPage($forPage);
    }
    
    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
    
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }
    
    public function getPerPage(): int
    {
        return $this->perPage;
    }
    
    public function setForPage(string $forPage): self
    {
        $this->forPage = $forPage;
        return $this;
    }
    
    public function getForPage(): string
    {
        return $this->forPage;
    }
    
    public function setStyling(string $styling): self
    {
        $this->styling = $styling;
        return $this;
    }
    
    public function getStyling(): string
    {
        return $this->styling;
    }
    
    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
    
    public function setCssFramework(string $cssFramework): self
    {
        if (!in_array($cssFramework, self::SUPPORTED_FRAMEWORKS, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Framework CSS non supporté. Utilisez un des frameworks suivants : %s',
                    implode(', ', self::SUPPORTED_FRAMEWORKS)
                )
            );
        }
        
        $this->cssFramework = $cssFramework;
        return $this;
    }
    
    public function getCssFramework(): string
    {
        return $this->cssFramework;
    }
}
