<?php

declare(strict_types=1);

namespace Tests\Integration;

use PDO;
use PHPUnit\Framework\TestCase;
use Cocoon\Pager\PaginatorConfig;
use Cocoon\Pager\Paginator;

class PaginatorSQLiteTest extends TestCase
{
    private PDO $pdo;
    private array $testData = [];

    protected function setUp(): void
    {
        // Créer une connexion SQLite en mémoire
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Créer la table de test
        $this->pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                email TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        // Insérer des données de test
        $stmt = $this->pdo->prepare('
            INSERT INTO users (name, email) 
            VALUES (:name, :email)
        ');

        // Générer 50 utilisateurs de test
        for ($i = 1; $i <= 50; $i++) {
            $stmt->execute([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com"
            ]);
        }

        // Récupérer toutes les données en tableau
        $this->testData = $this->pdo->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function testPaginationWithSQLite(): void
    {
        // Configurer la pagination avec le tableau de données
        $config = new PaginatorConfig(
            $this->testData,
            count($this->testData)
        );
        $config->setPerPage(10)
            ->setCssFramework('bootstrap5')
            ->setStyling('all');
        
        $paginator = new Paginator($config);
        
        // Vérifier le nombre total d'éléments
        $this->assertSame(50, $config->getTotal());
        
        // Vérifier le nombre d'éléments par page
        $this->assertCount(10, $paginator->items());
        
        // Vérifier le nombre total de pages
        $this->assertSame(5, $paginator->count());
        
        // Vérifier que les éléments sont correctement paginés
        $items = $paginator->items();
        $this->assertSame("User 1", $items[0]['name']);
        $this->assertSame("user1@example.com", $items[0]['email']);
    }

    public function testPaginationWithDifferentPageSizes(): void
    {
        $config = new PaginatorConfig(
            $this->testData,
            count($this->testData)
        );
        $config->setPerPage(5);
        
        $paginator = new Paginator($config);
        
        $this->assertCount(5, $paginator->items());
        $this->assertSame(10, $paginator->count()); // 50/5 = 10 pages
    }

    public function testPaginationWithNoResults(): void
    {
        // Simuler une requête sans résultats
        $emptyData = [];
        $config = new PaginatorConfig($emptyData, 0);
        $paginator = new Paginator($config);
        
        $this->assertEmpty($paginator->items());
        $this->assertSame(0, $paginator->count());
    }

    public function testPaginationNavigation(): void
    {
        $config = new PaginatorConfig(
            $this->testData,
            count($this->testData)
        );
        $config->setPerPage(10);
        
        // Simuler la navigation vers la page 2
        $_GET['page'] = '2';
        $paginator = new Paginator($config);
        
        $items = $paginator->items();
        $this->assertCount(10, $items);
        $this->assertSame("User 11", $items[0]['name']);
        $this->assertSame("user11@example.com", $items[0]['email']);
        
        // Simuler la navigation vers la dernière page
        $_GET['page'] = '5';
        $paginator = new Paginator($config);
        
        $items = $paginator->items();
        $this->assertCount(10, $items);
        $this->assertSame("User 41", $items[0]['name']);
        $this->assertSame("user41@example.com", $items[0]['email']);
        
        // Réinitialiser $_GET
        unset($_GET['page']);
    }

    public function testPaginationWithInvalidPage(): void
    {
        $config = new PaginatorConfig(
            $this->testData,
            count($this->testData)
        );
        $config->setPerPage(10);
        
        // Tester une page négative
        $_GET['page'] = '-1';
        $paginator = new Paginator($config);
        $this->assertSame(1, $paginator->currentPage());
        
        // Tester une page trop grande
        $_GET['page'] = '999';
        $paginator = new Paginator($config);
        $this->assertSame(5, $paginator->count()); // Devrait retourner le nombre total de pages
        $this->assertSame(5, $paginator->currentPage()); // Devrait être ramené à la dernière page
        
        // Tester une page non numérique
        $_GET['page'] = 'abc';
        $paginator = new Paginator($config);
        $this->assertSame(1, $paginator->currentPage());
        
        // Réinitialiser $_GET
        unset($_GET['page']);
    }

    protected function tearDown(): void
    {
        // Nettoyer la base de données
        $this->pdo->exec('DROP TABLE IF EXISTS users');
        unset($this->pdo);
    }
} 