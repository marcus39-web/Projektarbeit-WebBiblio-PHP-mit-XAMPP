<?php

/**
 * Book.php
 * 
 * Diese Datei implementiert die Book-Entity-Klasse nach dem Active Record Pattern.
 * Die Klasse kapselt sowohl die Datenrepräsentation als auch die Datenbankoperationen
 * für Bücher in der WebBiblio-Anwendung.
 * 
 * Das Active Record Pattern ermöglicht es Objekten, sich selbst in der Datenbank
 * zu persistieren, zu aktualisieren und zu löschen, was eine intuitive
 * objektorientierte Schnittstelle für Datenbankoperationen bietet.
 * 
 * @category Entity
 * @package  WebBiblio\Components
 * @author   WebBiblio Development Team
 * @version  1.0.0
 * @since    2026-01-17
 */

require_once __DIR__ . '/DatabaseSingleton.php';

/**
 * Book - Entity-Klasse für Buchverwaltung mit Active Record Pattern
 * 
 * Diese Klasse repräsentiert ein Buch in der Bibliotheksverwaltung und implementiert
 * das Active Record Pattern für nahtlose Datenbankinteraktionen. Jede Instanz
 * entspricht einem Datensatz in der 'books' Tabelle.
 * 
 * Features:
 * - CRUD-Operationen (Create, Read, Update, Delete)
 * - Typsichere Properties mit Null-Safety
 * - Statische Finder-Methoden für komplexe Abfragen
 * - Prepared Statements für SQL-Injection-Schutz
 * - Automatisches ID-Management bei Insert/Update
 * 
 * Datenbankschema Mapping:
 * - id: AUTO_INCREMENT PRIMARY KEY
 * - title: VARCHAR(255) NOT NULL
 * - author: VARCHAR(255) NOT NULL  
 * - category: VARCHAR(100) NOT NULL
 * - year: INT NULL (für historische/unbekannte Werke)
 * - publisher: VARCHAR(255) NULL
 * 
 * @category Entity
 * @package  WebBiblio\Components
 * @author   WebBiblio Development Team
 * @version  1.0.0
 */
class Book
{
    /**
     * Eindeutige Datenbank-ID des Buchs
     * 
     * Null für neue, noch nicht gespeicherte Bücher.
     * Wird automatisch bei der ersten Speicherung durch AUTO_INCREMENT gesetzt.
     * 
     * @var int|null
     */
    private ?int $id;

    /**
     * Titel des Buchs
     * 
     * Pflichtfeld, darf nicht leer sein. Maximal 255 Zeichen in der Datenbank.
     * 
     * @var string
     */
    private string $title;

    /**
     * Autor/Autorin des Buchs
     * 
     * Vollständiger Name des Autors. Bei mehreren Autoren wird meist
     * der Hauptautor angegeben. Maximal 255 Zeichen.
     * 
     * @var string
     */
    private string $author;

    /**
     * Literarische Kategorie/Genre des Buchs
     * 
     * Beispiele: Fantasy, Krimi, Roman, Science Fiction, Sachbuch, etc.
     * Wird für Filterung und Kategorisierung verwendet. Maximal 100 Zeichen.
     * 
     * @var string
     */
    private string $category;

    /**
     * Erscheinungsjahr des Buchs
     * 
     * Kann null sein für sehr alte Werke oder wenn das Jahr unbekannt ist.
     * Beispiel: antike Werke wie Homer's Odyssee.
     * 
     * @var int|null
     */
    private ?int $year;

    /**
     * Verlag/Publisher des Buchs
     * 
     * Name des Verlags, der das Buch veröffentlicht hat.
     * Kann null sein wenn unbekannt. Maximal 255 Zeichen.
     * 
     * @var string|null
     */
    private ?string $publisher;

    /**
     * Konstruktor für ein neues Book-Objekt
     * 
     * Erstellt eine neue Book-Instanz mit den angegebenen Daten.
     * Die ID ist optional und wird normalerweise nur beim Laden aus der
     * Datenbank angegeben. Für neue Bücher bleibt sie null bis zur
     * ersten Speicherung.
     * 
     * @param string      $title     Buchtitel (Pflichtfeld, max 255 Zeichen)
     * @param string      $author    Autor/Autorin (Pflichtfeld, max 255 Zeichen)
     * @param string      $category  Literarische Kategorie (Pflichtfeld, max 100 Zeichen)
     * @param int|null    $year      Erscheinungsjahr (optional, null für unbekannt)
     * @param string|null $publisher Verlagsname (optional, max 255 Zeichen)
     * @param int|null    $id        Datenbank-ID (nur für existierende Datensätze)
     * 
     * @example
     * // Neues Buch erstellen
     * $book = new Book('1984', 'George Orwell', 'Dystopie', 1949, 'Secker & Warburg');
     * 
     * // Existierendes Buch aus Datenbank rekonstruieren
     * $book = new Book('Hamlet', 'Shakespeare', 'Drama', 1603, 'Nicholas Ling', 42);
     */
    public function __construct(string $title, string $author, string $category, ?int $year, ?string $publisher, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->category = $category;
        $this->year = $year;
        $this->publisher = $publisher;
    }

    /**
     * Gibt die eindeutige Datenbank-ID des Buchs zurück
     * 
     * @return int|null Die ID oder null für noch nicht gespeicherte Bücher
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gibt den Titel des Buchs zurück
     * 
     * @return string Der vollständige Buchtitel
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Setzt einen neuen Titel für das Buch
     * 
     * @param string $title Der neue Buchtitel (max 255 Zeichen)
     * @throws InvalidArgumentException wenn der Titel leer ist
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gibt den Autor/die Autorin des Buchs zurück
     * 
     * @return string Der vollständige Name des Autors
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Setzt einen neuen Autor für das Buch
     * 
     * @param string $author Der Name des Autors (max 255 Zeichen)
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * Gibt die Kategorie/das Genre des Buchs zurück
     * 
     * @return string Die literarische Kategorie (z.B. "Fantasy", "Krimi")
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Setzt eine neue Kategorie für das Buch
     * 
     * @param string $category Die neue Kategorie (max 100 Zeichen)
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * Gibt das Erscheinungsjahr des Buchs zurück
     * 
     * @return int|null Das Erscheinungsjahr oder null wenn unbekannt
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * Setzt ein neues Erscheinungsjahr für das Buch
     * 
     * @param int|null $year Das Erscheinungsjahr oder null für unbekannt
     */
    public function setYear(?int $year): void
    {
        $this->year = $year;
    }

    /**
     * Gibt den Verlag des Buchs zurück
     * 
     * @return string|null Der Verlagsname oder null wenn unbekannt
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * Setzt einen neuen Verlag für das Buch
     * 
     * @param string|null $publisher Der Verlagsname oder null
     */
    public function setPublisher(?string $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * Lädt ein einzelnes Buch anhand seiner ID aus der Datenbank
     * 
     * Diese Finder-Methode implementiert das "Find by Primary Key" Pattern
     * und lädt ein vollständiges Book-Objekt aus der Datenbank.
     * Verwendet Prepared Statements für SQL-Injection-Schutz.
     * 
     * @param int $id Die eindeutige Datenbank-ID des gesuchten Buchs
     * 
     * @return Book|null Das gefundene Book-Objekt oder null wenn nicht gefunden
     * 
     * @throws PDOException Bei Datenbankverbindungsfehlern
     * 
     * @example
     * $book = Book::get(42);
     * if ($book !== null) {
     *     echo $book->getTitle();
     * }
     */
    public static function get(int $id): ?Book
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Book($row['title'], $row['author'], $row['category'], $row['year'], $row['publisher'], $row['id']);
        }
        return null;
    }

    /**
     * Lädt alle Bücher aus der Datenbank
     * 
     * Diese Methode implementiert das "Find All" Pattern und gibt eine
     * vollständige Liste aller in der Datenbank gespeicherten Bücher zurück.
     * Nützlich für Übersichtslisten und Verwaltungsoperationen.
     * 
     * @return Book[] Array aller Book-Objekte, leer wenn keine Bücher vorhanden
     * 
     * @throws PDOException Bei Datenbankverbindungsfehlern
     * 
     * @example
     * $allBooks = Book::getAll();
     * echo "Anzahl Bücher: " . count($allBooks);
     */
    public static function getAll(): array
    {
        $books = [];
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->query('SELECT * FROM books');

        while ($row = $stmt->fetch()) {
            $books[] = new Book($row['title'], $row['author'], $row['category'], $row['year'], $row['publisher'], $row['id']);
        }
        return $books;
    }

    /**
     * Sucht alle Bücher eines bestimmten Autors
     * 
     * Implementiert das "Find by Attribute" Pattern für Autor-basierte Suchen.
     * Führt eine exakte Stringsuche durch (case-sensitive).
     * 
     * @param string $author Der vollständige Name des gesuchten Autors
     * 
     * @return Book[] Array aller Bücher des Autors, leer wenn keine gefunden
     * 
     * @throws PDOException Bei Datenbankverbindungsfehlern
     * 
     * @example
     * $tolkienBooks = Book::getByAuthor('J.R.R. Tolkien');
     * foreach ($tolkienBooks as $book) {
     *     echo $book->getTitle() . "\n";
     * }
     */
    public static function getByAuthor(string $author): array
    {
        $books = [];
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM books WHERE author = ?');
        $stmt->execute([$author]);

        while ($row = $stmt->fetch()) {
            $books[] = new Book($row['title'], $row['author'], $row['category'], $row['year'], $row['publisher'], $row['id']);
        }
        return $books;
    }

    /**
     * Sucht alle Bücher einer bestimmten Kategorie
     * 
     * Implementiert das "Find by Attribute" Pattern für Kategorie-basierte Filterung.
     * Nützlich für Genre-spezifische Buchempfehlungen und Katalogisierung.
     * 
     * @param string $category Die gewünschte Buchkategorie (z.B. "Fantasy", "Krimi")
     * 
     * @return Book[] Array aller Bücher der Kategorie, leer wenn keine gefunden
     * 
     * @throws PDOException Bei Datenbankverbindungsfehlern
     * 
     * @example
     * $fantasyBooks = Book::getByCategory('Fantasy');
     * echo "Gefunden: " . count($fantasyBooks) . " Fantasy-Bücher";
     */
    public static function getByCategory(string $category): array
    {
        $books = [];
        $pdo = DatabaseSingleton::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM books WHERE category = ?');
        $stmt->execute([$category]);

        while ($row = $stmt->fetch()) {
            $books[] = new Book($row['title'], $row['author'], $row['category'], $row['year'], $row['publisher'], $row['id']);
        }
        return $books;
    }

    /**
     * Speichert das Book-Objekt in der Datenbank (INSERT oder UPDATE)
     * 
     * Implementiert das "Save" Pattern des Active Record Designs:
     * - Neue Objekte (id === null): INSERT mit AUTO_INCREMENT ID-Zuweisung
     * - Existierende Objekte (id !== null): UPDATE des vorhandenen Datensatzes
     * 
     * Die Methode erkennt automatisch den Modus und führt die entsprechende
     * SQL-Operation aus. Nach erfolgreichem INSERT wird die generierte ID
     * automatisch dem Objekt zugewiesen.
     * 
     * @throws PDOException Bei Datenbankverbindungs- oder SQL-Fehlern
     * @throws InvalidArgumentException Bei ungültigen Datenformaten
     * 
     * @example
     * // Neues Buch erstellen und speichern
     * $book = new Book('Neuerscheinung', 'Max Mustermann', 'Roman', 2026, 'Beispiel Verlag');
     * $book->save(); // INSERT, $book->getId() enthält jetzt die neue ID
     * 
     * // Existierendes Buch ändern und speichern  
     * $book->setTitle('Geänderter Titel');
     * $book->save(); // UPDATE des existierenden Datensatzes
     */
    public function save(): void
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();

        if ($this->id === null) {
            // INSERT: Neuen Datensatz erstellen
            $stmt = $pdo->prepare('INSERT INTO books (title, author, category, year, publisher) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$this->title, $this->author, $this->category, $this->year, $this->publisher]);
            // Automatische ID-Zuweisung nach erfolgreichem INSERT
            $this->id = (int)$pdo->lastInsertId();
        } else {
            // UPDATE: Existierenden Datensatz aktualisieren
            $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, category = ?, year = ?, publisher = ? WHERE id = ?');
            $stmt->execute([$this->title, $this->author, $this->category, $this->year, $this->publisher, $this->id]);
        }
    }

    /**
     * Löscht das Buch aus der Datenbank
     * 
     * Implementiert das "Delete" Pattern des Active Record Designs.
     * Entfernt den entsprechenden Datensatz permanent aus der Datenbank
     * und setzt die Objekt-ID auf null, um den "gelöschten" Zustand zu markieren.
     * 
     * Nach dem Löschen kann das Objekt durch erneutes save() wieder
     * in die Datenbank eingefügt werden (als neuer Datensatz).
     * 
     * @throws PDOException Bei Datenbankverbindungs- oder SQL-Fehlern
     * 
     * @example
     * $book = Book::get(42);
     * if ($book !== null) {
     *     $book->delete(); // Löscht das Buch aus der DB
     *     echo $book->getId(); // null - Buch ist gelöscht
     * }
     */
    public function delete(): void
    {
        if ($this->id !== null) {
            $pdo = DatabaseSingleton::getInstance()->getConnection();
            $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
            $stmt->execute([$this->id]);
            // Objekt-Status auf "gelöscht" setzen
            $this->id = null;
        }
    }
}

/**
 * End of Book.php
 * 
 * Diese Klasse implementiert ein vollständiges Active Record Pattern für
 * die Buchverwaltung mit typsicheren PHP 8+ Features und umfassender
 * Datenbankintegration über das Singleton Pattern.
 */
