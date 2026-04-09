<?php
// config/Database.php

class Database {
    // Dati di connessione
    private string $host     = 'localhost';
    private string $dbname   = 'socialapp';
    private string $username = 'root';
    private string $password = '';
    private string $charset  = 'utf8mb4';

    // Istanza singleton
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    // Costruttore privato — impedisce new Database() dall'esterno
    private function __construct() {}

    // Unico punto di accesso all'istanza
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Restituisce la connessione PDO, la crea se non esiste ancora
    public function getConnection(): PDO {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // lancia eccezioni sugli errori
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // risultati come array associativi
                PDO::ATTR_EMULATE_PREPARES   => false,                    // prepared statements nativi
            ];

            try {
                $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                // In produzione non esporre il messaggio reale
                http_response_code(500);
                echo json_encode(['error' => 'Errore di connessione al database']);
                exit;
            }
        }

        return $this->connection;
    }
}

// IN TUTTI GLI ALTRI FILE:
// // In qualsiasi controller o model:
// $pdo = Database::getInstance()->getConnection();

// $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
// $stmt->execute([$userId]);
// $user = $stmt->fetch();