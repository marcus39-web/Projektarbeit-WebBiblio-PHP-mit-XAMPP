<?php
// Book.php
// Klasse zur Repräsentation eines Buchobjekts und Datenbankoperationen (MySQL-Version)

require_once __DIR__ . '/DatabaseSingleton.php';

/**
 * Die Klasse Book repräsentiert ein Buch und bietet Methoden für den Datenbankzugriff.
 */
class Book
{
    // Attribute mit Typangaben
    private ?int $id;
    private string $title;
    private string $author;
    private string $category;
    private ?int $year;
    private ?string $publisher;

    /**
     * Konstruktor für ein Buchobjekt
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

    // Getter und Setter für die Attribute
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }
    public function getCategory(): string
    {
        return $this->category;
    }
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }
    public function getYear(): ?int
    {
        return $this->year;
    }
    public function setYear(?int $year): void
    {
        $this->year = $year;
    }
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }
    public function setPublisher(?string $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * Holt ein Buch anhand der ID aus der Datenbank.
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
     * Gibt alle Bücher als Array zurück.
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
     * Gibt alle Bücher eines bestimmten Autors zurück.
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
     * Gibt alle Bücher einer bestimmten Kategorie zurück.
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
     * Speichert das Buch in der Datenbank (neu oder Update).
     */
    public function save(): void
    {
        $pdo = DatabaseSingleton::getInstance()->getConnection();

        if ($this->id === null) {
            $stmt = $pdo->prepare('INSERT INTO books (title, author, category, year, publisher) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$this->title, $this->author, $this->category, $this->year, $this->publisher]);
            $this->id = (int)$pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, category = ?, year = ?, publisher = ? WHERE id = ?');
            $stmt->execute([$this->title, $this->author, $this->category, $this->year, $this->publisher, $this->id]);
        }
    }

    /**
     * Löscht das Buch aus der Datenbank.
     */
    public function delete(): void
    {
        if ($this->id !== null) {
            $pdo = DatabaseSingleton::getInstance()->getConnection();
            $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
            $stmt->execute([$this->id]);
            $this->id = null;
        }
    }
}
