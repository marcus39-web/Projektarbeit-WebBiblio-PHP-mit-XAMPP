<?php

/**
 * 001_create_books_table.php
 * 
 * Database Migration Script - Erstellt die Haupttabelle für das WebBiblio-System
 * 
 * Diese Datei implementiert eine Datenbank-Migration nach dem "Schema Migration" Pattern.
 * Sie erstellt die zentrale 'books' Tabelle mit allen erforderlichen Feldern für die
 * Buchverwaltung. Die Migration ist idempotent (wiederholbar) durch die Verwendung
 * von 'CREATE TABLE IF NOT EXISTS'.
 * 
 * Funktionen:
 * - Erstellt die 'books' Tabelle mit optimierter Struktur
 * - InnoDB Engine für ACID-Transaktionen und Foreign Keys
 * - UTF-8 Charset für internationale Zeichenunterstützung
 * - Auto-incrementing Primary Key für eindeutige Identifikation
 * - Browser- und CLI-kompatible Ausgabe
 * - Umfassendes Exception-Handling
 * 
 * Tabellen-Schema:
 * - id: AUTO_INCREMENT PRIMARY KEY (eindeutige Buch-ID)
 * - title: VARCHAR(255) NOT NULL (Buchtitel)
 * - author: VARCHAR(255) NOT NULL (Autor/Autorin)
 * - category: VARCHAR(255) NOT NULL (Genre/Kategorie)
 * - year: YEAR NULL (Erscheinungsjahr)
 * - publisher: VARCHAR(255) NULL (Verlag)
 * 
 * @category Database
 * @package  WebBiblio\Migrations
 * @author   WebBiblio Development Team
 * @version  1.0.0
 * @since    2026-01-17
 */

// Laden der DatabaseSingleton-Klasse für konsistente Datenbankverbindung
// Verwendet dasselbe Singleton-Pattern wie die Hauptanwendung
require_once 'DatabaseSingleton.php';

// Beginne Migrations-Prozess mit umfassendem Exception-Handling
// Wichtig: Migrations-Fehler sollen nicht die gesamte Anwendung zum Absturz bringen
try {
    // Datenbankverbindung über Singleton-Pattern herstellen
    // Gewährleistet konsistente Konfiguration mit der Hauptanwendung
    $db = DatabaseSingleton::getInstance();
    $pdo = $db->getConnection();

    // DDL-Statement: CREATE TABLE für die zentrale books-Tabelle
    // Verwendet IF NOT EXISTS für Idempotenz (Migration kann sicher wiederholt werden)
    // Schema-Details:
    // - id: Eindeutige ID als Primärschlüssel mit AUTO_INCREMENT
    // - title: Buchtitel (Pflichtfeld, max 255 Zeichen)
    // - author: Autor/Autorin (Pflichtfeld, max 255 Zeichen)
    // - category: Genre/Kategorie (Pflichtfeld, max 255 Zeichen)
    // - year: Erscheinungsjahr (optional, YEAR-Typ für 1901-2155)
    // - publisher: Verlag (optional, max 255 Zeichen)
    // - InnoDB für ACID-Transaktionen + utf8mb4 für Emoji-Support
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        category VARCHAR(255) NOT NULL,
        year YEAR,
        publisher VARCHAR(255)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    // SQL-Statement ausführen - DDL-Operationen brauchen keine Parameter-Bindung
    $pdo->exec($sql);

    // Erfolgs-Output: Unterscheidung zwischen Browser (HTTP) und CLI-Ausführung
    // REQUEST_URI ist nur in Webserver-Umgebung verfügbar
    if (!empty($_SERVER['REQUEST_URI'])) {
        // HTML-Output für Browser-Zugriff mit vollständigem Markup
        echo "<!DOCTYPE html><html><head><title>Migration Abgeschlossen</title></head><body>";
        echo "<h1>Datenbank-Setup Abgeschlossen</h1>";
        echo "<p>Tabelle 'books' erfolgreich erstellt.</p>";
        echo "<p><a href='../index.php'>→ Zu WebBiblio</a></p>";  // Navigation zurück zur App
        echo "</body></html>";
    } else {
        // Einfacher Text-Output für CLI/Terminal-Ausführung
        echo "Migration erfolgreich - Tabelle 'books' erstellt.\n";
    }
} catch (PDOException $e) {
    // Graceful Error-Handling: Migration-Fehler sollen benutzerfreundlich angezeigt werden
    // Häufige Ursachen: MySQL nicht gestartet, Datenbank 'library' fehlt, Berechtigungsprobleme

    if (!empty($_SERVER['REQUEST_URI'])) {
        // HTML-Fehlerseite für Browser mit XSS-Schutz
        echo "<!DOCTYPE html><html><head><title>Migrations-Fehler</title></head><body>";
        echo "<h1>Fehler</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";  // XSS-Schutz durch htmlspecialchars
        echo "<h3>Mögliche Lösungen:</h3>";
        echo "<ul>";
        echo "<li>Prüfen Sie, ob MySQL/XAMPP gestartet ist</li>";
        echo "<li>Stellen Sie sicher, dass die Datenbank 'library' existiert</li>";
        echo "<li>Prüfen Sie die Datenbankverbindungsparameter</li>";
        echo "</ul>";
        echo "</body></html>";
    } else {
        // CLI-Fehler-Output mit technischen Details für Entwickler
        echo "Migration fehlgeschlagen: " . $e->getMessage() . "\n";
        echo "Prüfen Sie die MySQL-Verbindung und Datenbank-Konfiguration.\n";
    }
}

/**
 * End of 001_create_books_table.php
 * 
 * Diese Migration stellt das Fundament für das WebBiblio-System bereit.
 * Nach erfolgreichem Ausführen kann die Anwendung Bücher verwalten.
 * 
 * Nächste Schritte:
 * 1. BookSeeder.php ausführen für Testdaten (optional)
 * 2. index.php öffnen für Anwendung
 * 
 * Wartung:
 * - Migration ist idempotent (kann sicher wiederholt werden)
 * - Bei Schema-Änderungen neue Migration-Dateien erstellen (002_, 003_, etc.)
 */
