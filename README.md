# WebBiblio - Einfache Bibliotheksverwaltung

Eine webbasierte PHP-Anwendung zur Verwaltung von Büchern in einer Bibliothek. WebBiblio bietet eine intuitive Benutzeroberfläche für das Hinzufügen, Bearbeiten, Löschen und Filtern von Büchern.

## Features

- **CRUD-Operationen**: Vollständige Buchverwaltung (Create, Read, Update, Delete)
- **Intelligente Filterung**: Bücher nach Autor oder Kategorie filtern
- **Responsive Design**: Funktioniert auf Desktop und mobilen Geräten
- **Sichere Implementierung**: SQL-Injection-Schutz und XSS-Prävention
- **Testdaten**: 50 kuratierte Beispielbücher für sofortigen Start
- **Moderne Architektur**: PHP 8+, PDO, Singleton Pattern, Active Record

## Technische Details

### Systemanforderungen
- **PHP**: 8.0 oder höher
- **MySQL**: 5.7 oder höher (oder MariaDB 10.2+)
- **Webserver**: Apache (XAMPP/WAMP) oder Nginx
- **Browser**: Moderne Browser mit HTML5-Unterstützung

### Architektur
- **Backend**: PHP mit PDO für Datenbankzugriff
- **Datenbank**: MySQL mit InnoDB-Engine
- **Design Patterns**: 
  - Singleton Pattern (DatabaseSingleton)
  - Active Record Pattern (Book-Klasse)
  - MVC-ähnliche Struktur
- **Sicherheit**: Prepared Statements, Input-Validierung, XSS-Schutz

## Installation

### 1. Voraussetzungen
Stellen Sie sicher, dass XAMPP (oder ähnlich) installiert und gestartet ist:
- Apache Webserver
- MySQL Datenbank

### 2. Projekt einrichten
```bash
# Repository klonen oder Dateien kopieren
cd C:\xampp\htdocs\
# Projektdateien in 'webbiblio' Ordner kopieren
```

### 3. Datenbank erstellen
```sql
-- MySQL/phpMyAdmin ausführen
CREATE DATABASE library CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Datenbank-Migration ausführen
Besuchen Sie im Browser:
```
http://localhost/webbiblio/Components/001_create_books_table.php
```
Dies erstellt die `books`-Tabelle automatisch.

### 5. Testdaten laden (Optional)
Besuchen Sie im Browser:
```
http://localhost/webbiblio/Components/BookSeeder.php
```
Lädt 50 Beispielbücher in die Datenbank.

### 6. Anwendung starten
Öffnen Sie im Browser:
```
http://localhost/webbiblio/
```

## Verwendung

### Bücher anzeigen
- Alle Bücher werden in einer übersichtlichen Tabelle angezeigt
- Zeigt Titel, Autor, Kategorie, Jahr und Verlag an

### Neues Buch hinzufügen
1. Formular "Neues Buch hinzufügen" ausfüllen
2. Titel, Autor und Kategorie sind Pflichtfelder
3. Jahr und Verlag sind optional
4. "Speichern" klicken

### Buch bearbeiten
1. "Bearbeiten"-Button beim gewünschten Buch klicken
2. Daten im Formular anpassen
3. "Aktualisieren" klicken

### Buch löschen
1. "Löschen"-Button beim gewünschten Buch klicken
2. Bestätigung in der Browser-Meldung

### Bücher filtern
- **Nach Autor**: Namen in das Autor-Filterfeld eingeben
- **Nach Kategorie**: Kategorie in das entsprechende Feld eingeben
- "Filtern" klicken oder Enter drücken

## Projektstruktur

```
PHP-Projektarbeit/
│
├── index.php                          # Hauptanwendung (UI + Controller)
├── README.md                          # Diese Dokumentation
│
└── Components/                        # Kernkomponenten
    ├── Book.php                       # Book-Entity (Active Record)
    ├── DatabaseSingleton.php          # Datenbankverbindung (Singleton)
    ├── BookSeeder.php                 # Testdaten-Generator
    └── 001_create_books_table.php     # Datenbank-Migration
```

## Datenbankschema

### Tabelle: `books`
| Spalte    | Typ           | Beschreibung                    |
|-----------|---------------|---------------------------------|
| id        | INT PK AI     | Eindeutige Buch-ID             |
| title     | VARCHAR(255)  | Buchtitel (Pflichtfeld)        |
| author    | VARCHAR(255)  | Autor/Autorin (Pflichtfeld)    |
| category  | VARCHAR(255)  | Genre/Kategorie (Pflichtfeld)  |
| year      | YEAR          | Erscheinungsjahr (optional)    |
| publisher | VARCHAR(255)  | Verlag (optional)              |

## 🔧 Konfiguration

### Datenbankverbindung
Standardkonfiguration in `Components/DatabaseSingleton.php`:
- **Host**: localhost
- **Port**: 3306  
- **Datenbank**: library
- **Benutzer**: root
- **Passwort**: (leer - XAMPP Standard)

Bei Bedarf anpassen:
```php
private function __construct()
{
    $host = 'localhost';     // Ihr MySQL-Server
    $port = 3306;           // Ihr MySQL-Port  
    $dbname = 'library';    // Ihre Datenbank
    $username = 'root';     // Ihr MySQL-Benutzer
    $password = '';         // Ihr MySQL-Passwort
    // ...
}
```

## Sicherheitsfeatures

- **SQL-Injection-Schutz**: Alle Datenbankabfragen verwenden Prepared Statements
- **XSS-Prävention**: Alle Ausgaben werden mit `htmlspecialchars()` gesäubert
- **Input-Validierung**: Server- und clientseitige Validierung von Formulardaten
- **Fehlerbehandlung**: Graceful Error-Handling ohne Preisgabe sensibler Informationen

## API-Referenz

### Book-Klasse Methoden

```php
// Einzelnes Buch laden
$book = Book::get(42);

// Alle Bücher laden  
$books = Book::getAll();

// Bücher nach Autor filtern
$books = Book::getByAuthor('J.R.R. Tolkien');

// Bücher nach Kategorie filtern  
$books = Book::getByCategory('Fantasy');

// Neues Buch erstellen und speichern
$book = new Book('Titel', 'Autor', 'Kategorie', 2026, 'Verlag');
$book->save();

// Buch aktualisieren
$book->setTitle('Neuer Titel');
$book->save();

// Buch löschen
$book->delete();
```

## Testdaten

Der BookSeeder enthält 50 kuratierte Bücher verschiedener Genres:
- Klassische Literatur (Tolstoi, Kafka, Shakespeare)
- Science Fiction (Asimov, Herbert, Gibson) 
- Fantasy (Tolkien, Lewis, Rowling)
- Krimis (Christie, Doyle, Hammett)
- Moderne Literatur (Morrison, McCarthy)
- Sachbücher (Programmierung, Philosophie)

## Fehlerbehebung

### MySQL-Verbindungsfehler
- Prüfen Sie, ob XAMPP/MySQL läuft
- Vergewissern Sie sich, dass die Datenbank 'library' existiert
- Überprüfen Sie die Zugangsdaten in DatabaseSingleton.php

### Leere Buchsliste  
- Führen Sie den BookSeeder aus: `/Components/BookSeeder.php`
- Prüfen Sie die Datenbank-Migration: `/Components/001_create_books_table.php`

### Formular-Fehler
- Stellen Sie sicher, dass Titel, Autor und Kategorie ausgefüllt sind
- Prüfen Sie die Browser-Konsole auf JavaScript-Fehler

## Lizenz

Dieses Projekt wurde als Bildungsprojekt entwickelt und steht für Lern- und Demonstrationszwecke zur Verfügung.

## Beiträge

WebBiblio wurde als Projektarbeit entwickelt und demonstriert moderne PHP-Entwicklungspraktiken:
- Objektorientierte Programmierung
- Design Patterns (Singleton, Active Record)
- Sichere Datenbankprogrammierung
- Benutzerfreundliche Webentwicklung

---

**WebBiblio** - Ihre einfache Lösung für die digitale Buchverwaltung! 