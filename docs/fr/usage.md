# Guide d'utilisation de la Pagination

Ce guide explique comment utiliser la pagination avec différentes sources de données.

## Table des matières
- [Utilisation avec un tableau PHP](#utilisation-avec-un-tableau-php)
- [Utilisation avec PDO (SQLite/MySQL)](#utilisation-avec-pdo)
- [Utilisation avec Laravel Query Builder](#utilisation-avec-laravel-query-builder)

## Utilisation avec un tableau PHP

L'utilisation la plus simple est avec un tableau PHP :

```php
$data = [/* vos données */];
$total = count($data);

$config = new PaginatorConfig($data, $total);
$config->setPerPage(10)
    ->setCssFramework('bootstrap5')
    ->setStyling('all');

$paginator = new Paginator($config);

// Afficher les éléments de la page courante
foreach ($paginator->items() as $item) {
    echo $item['name'];
}

// Afficher la pagination
echo $paginator->render();
```

## Utilisation avec PDO

Voici comment utiliser la pagination avec PDO (SQLite ou MySQL) :

```php
// Connexion à la base de données
$pdo = new PDO('sqlite::memory:'); // ou 'mysql:host=localhost;dbname=test'
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer le nombre total d'enregistrements
$total = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

// Récupérer les données
$query = 'SELECT * FROM users';
$data = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Configurer la pagination
$config = new PaginatorConfig($data, $total);
$config->setPerPage(10)
    ->setCssFramework('bootstrap5')
    ->setStyling('all');

$paginator = new Paginator($config);

// Afficher les résultats
foreach ($paginator->items() as $user) {
    echo $user['name'] . ' - ' . $user['email'] . '<br>';
}

// Afficher la pagination
echo $paginator->render();
```

## Utilisation avec Laravel Query Builder

Si vous utilisez Laravel, vous pouvez utiliser directement le Query Builder :

```php
use Illuminate\Database\Capsule\Manager as DB;

// Configuration de la connexion (si vous n'utilisez pas Laravel)
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => ':memory:',
    // ... autres options de connexion
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Créer la requête
$query = DB::table('users');
$total = $query->count();

// Configurer la pagination directement avec le Query Builder
$config = new PaginatorConfig($query, $total);
$config->setPerPage(10)
    ->setCssFramework('bootstrap5')
    ->setStyling('all');

$paginator = new Paginator($config);

// Afficher les résultats
foreach ($paginator->items() as $user) {
    echo $user->name . ' - ' . $user->email . '<br>';
}

// Afficher la pagination
echo $paginator->render();
```

## Personnalisation

### Styles de pagination disponibles
- `all` : Affiche tous les numéros de page
- `basic` : Style simple avec Précédent/Suivant
- `elastic` : Style élastique qui s'adapte
- `sliding` : Style coulissant

### Frameworks CSS supportés
- `bootstrap4` : Pour Bootstrap 4
- `bootstrap5` : Pour Bootstrap 5
- `tailwind` : Pour Tailwind CSS

### Options de configuration
```php
$config->setPerPage(10); // Nombre d'éléments par page
$config->setStyling('all'); // Style de pagination
$config->setCssFramework('bootstrap5'); // Framework CSS
```

### Paramètres d'URL
Par défaut, la pagination utilise le paramètre `page` dans l'URL. Vous pouvez le personnaliser :

```php
$config = new PaginatorConfig($data, $total, 'p'); // Utilisera ?p=2 au lieu de ?page=2
```

### Ajouter des paramètres à l'URL
```php
$paginator->appends(['sort' => 'name', 'order' => 'desc']);
// Résultat : ?page=2&sort=name&order=desc
``` 