![License](https://img.shields.io/badge/Licence-MIT-green) [![codecov](https://codecov.io/gh/cocoon-projet/pagination/graph/badge.svg?token=WRFF3E0PA2)](https://codecov.io/gh/cocoon-projet/pagination)

# Cocoon Pagination

Une librairie de pagination PHP moderne et flexible avec support pour Bootstrap 4, Bootstrap 5 et Tailwind CSS.

## Pré-requis

![PHP Version](https://img.shields.io/badge/php:version-8.0-blue)

## Installation

```bash
composer require cocoon/pagination
```

## Caractéristiques

- Support de PHP 8.0+
- Styles de pagination multiples (basic, all, elastic, sliding)
- Support des frameworks CSS (Bootstrap 4, Bootstrap 5, Tailwind CSS)
- Pagination de différents types de données :
  - Tableaux PHP
  - Laravel Query Builder
  - Cocoon ORM Query Builder
- Configuration flexible
- Chaînage des méthodes
- Typage strict
- PSR-12 compliant

## Utilisation basique

```php
// Création d'une configuration
$config = new PaginatorConfig(
    $data,      // Données à paginer (array ou query builder)
    $total,     // Nombre total d'éléments
    'page'      // Paramètre GET pour la page (optionnel)
);

// Configuration des options
$config->setPerPage(10)           // Nombre d'éléments par page
       ->setStyling('basic')      // Style de pagination
       ->setCssFramework('bootstrap5'); // Framework CSS

// Création du paginateur
$paginator = new Paginator($config);

// Récupération des éléments de la page courante
$items = $paginator->items();

// Affichage de la pagination
echo $paginator->render('custom-class');
```

## Styles de pagination

### Basic
Affiche uniquement les boutons "Précédent" et "Suivant" avec le numéro de page courant.

### All
Affiche tous les numéros de page avec les boutons "Précédent" et "Suivant".

### Elastic
Affiche un nombre variable de pages autour de la page courante.

### Sliding
Affiche une fenêtre glissante de pages autour de la page courante.

## Frameworks CSS supportés

### Bootstrap 4
```php
$config->setCssFramework('bootstrap4');
```

### Bootstrap 5
```php
$config->setCssFramework('bootstrap5');
```

### Tailwind CSS
```php
$config->setCssFramework('tailwind');
```

## API

### Classe Paginator

#### Méthodes principales
- `items(): array` - Récupère les éléments de la page courante
- `render(string $class = ''): string` - Affiche la pagination
- `currentPage(): int` - Retourne le numéro de la page courante
- `hasPages(): bool` - Vérifie s'il y a plusieurs pages
- `count(): int` - Retourne le nombre total de pages

#### Personnalisation de l'URL
- `appends(array $params): self` - Ajoute des paramètres à l'URL

### Classe PaginatorConfig

#### Configuration
- `setPerPage(int $perPage): self` - Définit le nombre d'éléments par page
- `setStyling(string $style): self` - Définit le style de pagination
- `setCssFramework(string $framework): self` - Définit le framework CSS
- `setForPage(string $param): self` - Définit le paramètre GET pour la page

## Licence

MIT License. Voir le fichier LICENSE pour plus de détails.