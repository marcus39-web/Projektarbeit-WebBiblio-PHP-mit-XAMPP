<?php

/**
 * BookSeeder.php
 * 
 * Diese Klasse implementiert das Database Seeding Pattern für die WebBiblio-Anwendung.
 * Sie befüllt die Datenbank mit vordefinierten Testdaten zur Entwicklung und Demonstration.
 * 
 * Das Seeding erfolgt über eine statische Methode, die eine kuratierte Liste von
 * 50 bekannten Büchern aus verschiedenen Genres und Epochen in die Datenbank einträgt.
 * 
 * @category Database
 * @package  WebBiblio
 * @author   WebBiblio Development Team
 * @version  1.0.0
 * @since    2026-01-17
 */

require_once 'Components/Book.php';

/**
 * BookSeeder - Klasse zum Befüllen der Datenbank mit Testdaten
 * 
 * Diese Seeder-Klasse folgt dem Database Seeding Pattern und wird verwendet,
 * um die Buchverwaltungsdatenbank mit einer vordefinierten Sammlung von
 * Büchern zu füllen. Dies ist besonders nützlich für:
 * 
 * - Entwicklungstests mit realistischen Daten
 * - Demonstrationszwecke der Anwendung
 * - Qualitätssicherung und Funktionstests
 * - Prototyping und UI-Design-Validierung
 * 
 * @category Database
 * @package  WebBiblio\Components
 * @author   WebBiblio Development Team
 * @version  1.0.0
 */
class BookSeeder
{
    /**
     * Führt das Database Seeding durch und fügt Testbücher zur Datenbank hinzu
     * 
     * Diese Methode implementiert die Hauptfunktionalität des Seeders:
     * - Lädt eine kuratierte Liste von 50 bekannten Büchern
     * - Prüft auf bereits vorhandene Duplikate (Titel + Autor)
     * - Fügt nur neue Bücher hinzu, um Datenintegrität zu gewährleisten
     * - Protokolliert alle Aktionen mit HTML-Output für Benutzer-Feedback
     * - Behandelt Fehler graceful mit Exception-Handling
     * 
     * Die Buchdaten umfassen verschiedene Genres:
     * - Klassische Literatur (Tolstoi, Kafka, Shakespeare)
     * - Science Fiction (Asimov, Herbert, Gibson)
     * - Fantasy (Tolkien, Lewis, Rowling)
     * - Krimis (Christie, Doyle, Hammett)
     * - Moderne Literatur (Morrison, McCarthy)
     * - Programmierung und Sachbücher (Martin, Marx)
     * 
     * @throws Exception Wenn Datenbankverbindung fehlschlägt oder Book-Operationen scheitern
     * @return void
     * 
     * @example
     * BookSeeder::seed();
     * 
     * @since 1.0.0
     */
    public static function seed(): void
    {
        // Kuratierte Sammlung von 50 Büchern aus verschiedenen Genres und Epochen
        // Jedes Array-Element: [Titel, Autor, Kategorie, Erscheinungsjahr, Verlag]
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

        // Zähler für erfolgreich hinzugefügte Bücher
        $count = 0;

        // HTML-Output für Benutzer-Feedback beginnen
        echo "<h2>BookSeeder wird ausgeführt...</h2>";
        echo "<div style='font-family: monospace; background: #f5f5f5; padding: 10px; border-radius: 5px;'>";

        // Iteriere durch alle Bücher und versuche sie zur Datenbank hinzuzufügen
        foreach ($books as $bookData) {
            try {
                // Duplikat-Prüfung: Verhindere doppelte Einträge basierend auf Titel + Autor
                // Diese Prüfung gewährlesteist Datenintegrität und verhindert versehentliche Duplikate
                $existingBooks = Book::getAll();
                $exists = false;

                // Durchsuche alle vorhandenen Bücher nach exakter Titel-Autor-Kombination
                foreach ($existingBooks as $existing) {
                    if ($existing->getTitle() === $bookData[0] && $existing->getAuthor() === $bookData[1]) {
                        $exists = true;
                        break; // Frühzeitige Beendigung bei gefundenem Duplikat
                    }
                }

                // Nur neue Bücher hinzufügen (keine Duplikate)
                if (!$exists) {
                    // Neue Book-Instanz mit allen erforderlichen Daten erstellen
                    // Verwendung des Book-Konstruktors mit parametrisierter Initialisierung
                    $book = new Book(
                        $bookData[0], // title - Buchtitel
                        $bookData[1], // author - Autor/Autorin
                        $bookData[2], // category - Literaturgenre/Kategorie
                        $bookData[3], // year - Erscheinungsjahr (kann null sein für antike Werke)
                        $bookData[4]  // publisher - Verlagsname
                    );

                    // Buch in Datenbank persistieren über Active Record Pattern
                    $book->save();
                    $count++; // Erfolgszähler incrementieren

                    // Erfolgreiche Erstellung mit XSS-Schutz ausgeben
                    echo "Hinzugefügt: " . htmlspecialchars($bookData[0]) . " von " . htmlspecialchars($bookData[1]) . "<br>";
                } else {
                    // Informative Meldung bei bereits vorhandenem Buch
                    echo "Bereits vorhanden: " . htmlspecialchars($bookData[0]) . "<br>";
                }
            } catch (Exception $e) {
                // Graceful Error-Handling: Einzelne Fehler sollen nicht gesamten Seeding-Prozess stoppen
                // Protokolliere Fehler mit Buch-Information und Exception-Details
                echo "Fehler bei: " . htmlspecialchars($bookData[0]) . " - " . $e->getMessage() . "<br>";
            }
        }

        // HTML-Output abschließen und Zusammenfassung anzeigen
        echo "</div>";
        echo "<br><strong>Seeding abgeschlossen: {$count} neue Bücher hinzugefügt</strong><br>";
        echo "<p><a href='index.php'>Zurück zur Hauptseite</a></p>";
    }
}

/**
 * Auto-Execution Block
 * 
 * Dieser Block wird nur ausgeführt, wenn die Datei direkt über HTTP aufgerufen wird
 * (nicht über require/include). Dies ermöglicht sowohl die Verwendung als Klassen-Library
 * als auch als eigenständiges Seeding-Skript.
 * 
 * Pattern: Script-Dual-Mode (Library + Executable)
 */
if (basename($_SERVER['PHP_SELF']) === 'BookSeeder.php') {
    echo "<h1>BookSeeder für WebBiblio</h1>";

    try {
        // Hauptseeding-Prozess starten
        BookSeeder::seed();
    } catch (Exception $e) {
        // Umfassendes Error-Handling mit Benutzer-Hilfestellung
        // Zeigt häufige Setup-Probleme und deren Lösungen auf
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
