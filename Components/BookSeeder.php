<?php
// BookSeeder.php
// Seeder zum Befüllen der Datenbank mit Testdaten

require_once 'Components/Book.php';

class BookSeeder
{
    /**
     * Fügt 50 imaginäre Bücher zur Datenbank hinzu
     */
    public static function seed(): void
    {
        $books = [
            ['Der Herr der Ringe: Die Gefährten', 'J.R.R. Tolkien', 'Fantasy', 1954, 'Klett-Cotta'],
            ['1984', 'George Orwell', 'Dystopie', 1949, 'Secker & Warburg'],
            ['Clean Code', 'Robert C. Martin', 'Programmierung', 2008, 'Prentice Hall'],
            ['Harry Potter und der Stein der Weisen', 'J.K. Rowling', 'Fantasy', 1997, 'Bloomsbury'],
            ['Das Kapital', 'Karl Marx', 'Wirtschaft', 1867, 'Verlag Otto Meisner'],
            ['Stolz und Vorurteil', 'Jane Austen', 'Roman', 1813, 'T. Egerton'],
            ['Der große Gatsby', 'F. Scott Fitzgerald', 'Roman', 1925, 'Charles Scribners Sons'],
            ['Moby Dick', 'Herman Melville', 'Roman', 1851, 'Richard Bentley'],
            ['Krieg und Frieden', 'Leo Tolstoi', 'Roman', 1869, 'Russischer Bote'],
            ['Die Verwandlung', 'Franz Kafka', 'Roman', 1915, 'Kurt Wolff Verlag'],
            ['Fahrenheit 451', 'Ray Bradbury', 'Science Fiction', 1953, 'Ballantine Books'],
            ['Der Fänger im Roggen', 'J.D. Salinger', 'Roman', 1951, 'Little, Brown and Company'],
            ['Wer die Nachtigall stört', 'Harper Lee', 'Roman', 1960, 'J. B. Lippincott & Co.'],
            ['Die Bibel', 'Verschiedene Autoren', 'Religion', null, 'Verschiedene'],
            ['Der kleine Prinz', 'Antoine de Saint-Exupéry', 'Märchen', 1943, 'Reynal & Hitchcock'],
            ['Brave New World', 'Aldous Huxley', 'Science Fiction', 1932, 'Chatto & Windus'],
            ['Der Name der Rose', 'Umberto Eco', 'Krimi', 1980, 'Bompiani'],
            ['Midnight\'s Children', 'Salman Rushdie', 'Roman', 1981, 'Jonathan Cape'],
            ['Hundert Jahre Einsamkeit', 'Gabriel García Márquez', 'Magischer Realismus', 1967, 'Editorial Sudamericana'],
            ['Die Chroniken von Narnia', 'C.S. Lewis', 'Fantasy', 1950, 'Geoffrey Bles'],
            ['Dune', 'Frank Herbert', 'Science Fiction', 1965, 'Chilton Books'],
            ['Foundation', 'Isaac Asimov', 'Science Fiction', 1951, 'Gnome Press'],
            ['Der Hobbit', 'J.R.R. Tolkien', 'Fantasy', 1937, 'George Allen & Unwin'],
            ['Neuromancer', 'William Gibson', 'Cyberpunk', 1984, 'Ace Books'],
            ['Der Outsider', 'Albert Camus', 'Philosophie', 1942, 'Gallimard'],
            ['Anna Karenina', 'Leo Tolstoi', 'Roman', 1877, 'Russischer Bote'],
            ['Ulysses', 'James Joyce', 'Roman', 1922, 'Sylvia Beach'],
            ['Lolita', 'Vladimir Nabokov', 'Roman', 1955, 'Olympia Press'],
            ['Auf der Suche nach der verlorenen Zeit', 'Marcel Proust', 'Roman', 1913, 'Bernard Grasset'],
            ['Die Odyssee', 'Homer', 'Epos', null, 'Antike Überlieferung'],
            ['Madame Bovary', 'Gustave Flaubert', 'Roman', 1857, 'Michel Lévy Frères'],
            ['Die göttliche Komödie', 'Dante Alighieri', 'Epos', 1320, 'Mittelalterliche Handschriften'],
            ['Hamlet', 'William Shakespeare', 'Drama', 1603, 'Nicholas Ling und John Trundell'],
            ['Don Quijote', 'Miguel de Cervantes', 'Roman', 1605, 'Juan de la Cuesta'],
            ['Wuthering Heights', 'Emily Brontë', 'Roman', 1847, 'Thomas Cautley Newby'],
            ['Jane Eyre', 'Charlotte Brontë', 'Roman', 1847, 'Smith, Elder & Co.'],
            ['Die Schatzinsel', 'Robert Louis Stevenson', 'Abenteuer', 1883, 'Cassell & Company'],
            ['Dracula', 'Bram Stoker', 'Horror', 1897, 'Archibald Constable and Company'],
            ['Frankenstein', 'Mary Shelley', 'Horror', 1818, 'Lackington, Hughes, Harding, Mavor & Jones'],
            ['Dr. Jekyll und Mr. Hyde', 'Robert Louis Stevenson', 'Horror', 1886, 'Longmans, Green & Co.'],
            ['Die Abenteuer des Sherlock Holmes', 'Arthur Conan Doyle', 'Krimi', 1892, 'George Newnes'],
            ['Mord im Orient Express', 'Agatha Christie', 'Krimi', 1934, 'Collins Crime Club'],
            ['Der Malteser Falke', 'Dashiell Hammett', 'Krimi', 1930, 'Alfred A. Knopf'],
            ['Der Pate', 'Mario Puzo', 'Krimi', 1969, 'G. P. Putnam\'s Sons'],
            ['Catch-22', 'Joseph Heller', 'Satire', 1961, 'Simon & Schuster'],
            ['Slaughterhouse-Five', 'Kurt Vonnegut', 'Science Fiction', 1969, 'Delacorte Press'],
            ['One Flew Over the Cuckoo\'s Nest', 'Ken Kesey', 'Roman', 1962, 'Viking Press'],
            ['Die Farbe Lila', 'Alice Walker', 'Roman', 1982, 'Harcourt Brace Jovanovich'],
            ['Beloved', 'Toni Morrison', 'Roman', 1987, 'Alfred A. Knopf'],
            ['The Road', 'Cormac McCarthy', 'Postapokalyptisch', 2006, 'Alfred A. Knopf']
        ];

        $count = 0;
        echo "<h2>BookSeeder wird ausgeführt...</h2>";
        echo "<div style='font-family: monospace; background: #f5f5f5; padding: 10px; border-radius: 5px;'>";

        foreach ($books as $bookData) {
            try {
                // Prüfen ob Buch bereits existiert
                $existingBooks = Book::getAll();
                $exists = false;
                foreach ($existingBooks as $existing) {
                    if ($existing->getTitle() === $bookData[0] && $existing->getAuthor() === $bookData[1]) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $book = new Book(
                        $bookData[0], // title
                        $bookData[1], // author
                        $bookData[2], // category
                        $bookData[3], // year
                        $bookData[4]  // publisher
                    );
                    $book->save();
                    $count++;
                    echo "Hinzugefügt: " . htmlspecialchars($bookData[0]) . " von " . htmlspecialchars($bookData[1]) . "<br>";
                } else {
                    echo "Bereits vorhanden: " . htmlspecialchars($bookData[0]) . "<br>";
                }
            } catch (Exception $e) {
                echo "Fehler bei: " . htmlspecialchars($bookData[0]) . " - " . $e->getMessage() . "<br>";
            }
        }

        echo "</div>";
        echo "<br><strong>Seeding abgeschlossen: {$count} neue Bücher hinzugefügt</strong><br>";
        echo "<p><a href='index.php'>Zurück zur Hauptseite</a></p>";
    }
}

// Wenn Datei direkt aufgerufen wird, Seeding ausführen
if (basename($_SERVER['PHP_SELF']) === 'BookSeeder.php') {
    echo "<h1>BookSeeder für WebBiblio</h1>";
    try {
        BookSeeder::seed();
    } catch (Exception $e) {
        echo "<div style='color: red; padding: 10px; background: #ffe6e6; border-radius: 5px;'>";
        echo "Fehler beim Seeding: " . htmlspecialchars($e->getMessage());
        echo "<br><br>Stellen Sie sicher, dass:";
        echo "<ul>";
        echo "<li>MySQL läuft (Port 3306)</li>";
        echo "<li>Datenbank 'library' existiert</li>";
        echo "<li>Tabelle 'books' existiert (führen Sie zuerst <a href='Components/001_create_books_table.php'>die Migration</a> aus)</li>";
        echo "</ul>";
        echo "</div>";
    }
}
