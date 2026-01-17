<?php

/**
 * DatabaseSingleton.php
 * WebBiblio - Datenbankverbindungs-Singleton für MySQL
 * 
 * Implementiert das Singleton-Designpattern für die Datenbankverbindung.
 * Stellt sicher, dass nur eine PDO-Instanz zur MySQL-Datenbank existiert.
 * 
 * Vorteile des Singleton-Patterns:
 * - Ressourcenschonung (eine Verbindung für alle DB-Operationen)
 * - Konsistente Datenbankeinstellungen
 * - Zentrale Konfiguration aller PDO-Optionen
 * 
 * @author  WebBiblio-Projekt
 * @version 1.0
 * @since   PHP 8.0+
 */

/**
 * Singleton-Klasse für MySQL-Datenbankverbindungen
 * 
 * Diese Klasse implementiert das Singleton-Designpattern zur Verwaltung
 * einer einzigen PDO-Datenbankverbindung zur MySQL-Datenbank 'library'.
 * 
 * Designpattern: Singleton
 * - Konstruktor ist private (verhindert direkte Instanziierung)
 * - Statische getInstance()-Methode für kontrollierten Zugriff
 * - Statische $instance-Variable speichert einzige Instanz
 * 
 * Verwendung:
 * $db = DatabaseSingleton::getInstance();
 * $pdo = $db->getConnection();
 */
class DatabaseSingleton
{
    /**
     * Statische Variable für die einzige Singleton-Instanz
     * @var DatabaseSingleton|null Singleton-Instanz oder null
     */
    private static ?DatabaseSingleton $instance = null;

    /**
     * PDO-Datenbankverbindung zur MySQL-Datenbank
     * @var PDO MySQL-Datenbankverbindungsobjekt
     */
    private PDO $connection;

    /**
     * Privater Konstruktor (Singleton-Pattern)
     * 
     * Verhindert direkte Instanziierung von außerhalb der Klasse.
     * Baut die MySQL-Datenbankverbindung mit optimierten PDO-Einstellungen auf.
     * 
     * Datenbankparameter:
     * - Host: localhost (lokaler MySQL-Server)
     * - Port: 3306 (Standard MySQL-Port)
     * - Datenbank: library (WebBiblio-Datenbank)
     * - Benutzer: root (Entwicklungsumgebung)
     * - Passwort: leer (XAMPP-Standard)
     * - Charset: utf8mb4 (vollständiger UTF-8 Support)
     * 
     * @throws PDOException Falls Datenbankverbindung fehlschlägt
     */
    private function __construct()
    {
        // Datenbankverbindungsparameter für lokale XAMPP-Umgebung
        $host = 'localhost';        // MySQL-Server (lokal)
        $port = '3306';             // Standard MySQL-Port
        $dbname = 'library';        // WebBiblio-Datenbank
        $username = 'root';         // XAMPP-Standard-Benutzer
        $password = '';             // XAMPP-Standard (kein Passwort)
        $charset = 'utf8mb4';       // Vollständiger UTF-8 Support (inkl. Emojis)

        // Data Source Name (DSN) für MySQL-Verbindung zusammenbauen
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

        // PDO-Optionen für optimale Sicherheit und Performance
        $options = [
            // Fehlerbehandlung: Exceptions werfen bei Datenbankfehlern
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Fetch-Modus: Assoziative Arrays als Standard-Rückgabe
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Prepared Statements: Echte Prepared Statements verwenden (Sicherheit)
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        // PDO-Verbindung mit konfigurierten Optionen aufbauen
        $this->connection = new PDO($dsn, $username, $password, $options);
    }

    /**
     * Statische Methode zur Rückgabe der Singleton-Instanz
     * 
     * Implementiert das Singleton-Pattern durch Lazy Initialization:
     * - Bei erstem Aufruf: Neue Instanz erstellen und speichern
     * - Bei weiteren Aufrufen: Gespeicherte Instanz zurückgeben
     * 
     * Dies gewährleistet, dass nur eine Datenbankverbindung existiert.
     * 
     * @return DatabaseSingleton Einzige Instanz der Klasse
     * @throws PDOException Falls Datenbankverbindung fehlschlägt
     */
    public static function getInstance(): DatabaseSingleton
    {
        // Lazy Initialization: Instanz nur bei Bedarf erstellen
        if (self::$instance === null) {
            self::$instance = new DatabaseSingleton();
        }
        return self::$instance;
    }

    /**
     * Rückgabe der PDO-Datenbankverbindung
     * 
     * Stellt die konfigurierte PDO-Instanz für Datenbankoperationen zur Verfügung.
     * Die Verbindung ist bereits mit optimalen Sicherheits- und Performance-Einstellungen konfiguriert.
     * 
     * Verwendung durch andere Klassen:
     * $pdo = DatabaseSingleton::getInstance()->getConnection();
     * $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
     * 
     * @return PDO Konfigurierte MySQL-Datenbankverbindung
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
