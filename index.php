<?php

/**
 * index.php
 * WebBiblio - Einfache Bibliotheksverwaltung
 * 
 * Hauptseite der Webanwendung für die Buchverwaltung.
 * Bietet CRUD-Funktionalitäten (Create, Read, Update, Delete) für Bücher.
 * 
 * Features:
 * - Bücher anzeigen, hinzufügen, bearbeiten und löschen
 * - Filter nach Autor und Kategorie
 * - Input-Validierung und XSS-Schutz
 * - Responsive Design mit einfachem CSS
 */

/**
 * Autoloading-Mechanismus für Klassen
 * 
 * Implementiert PSR-4-ähnliches Autoloading:
 * 1. Sucht zuerst im Components-Verzeichnis
 * 2. Fallback zum Root-Verzeichnis falls nicht gefunden
 * 
 * Lädt automatisch Book.php und DatabaseSingleton.php bei Bedarf
 */
spl_autoload_register(function ($class) {
    // Primärer Pfad: Components-Verzeichnis
    $file = __DIR__ . '/Components/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Fallback: Root-Verzeichnis für zusätzliche Klassen
        $file = __DIR__ . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

/**
 * CRUD-Operation: CREATE (Neues Buch hinzufügen)
 * 
 * Verarbeitet POST-Requests vom "Buch hinzufügen"-Formular.
 * Führt Input-Validierung durch und erstellt neues Book-Objekt.
 * Verwendet PRG-Pattern (Post-Redirect-Get) zur Vermeidung doppelter Submissions.
 */
if (isset($_POST['add_book'])) {
    // Server-seitige Input-Validierung für Pflichtfelder
    if (empty(trim($_POST['title'])) || empty(trim($_POST['author'])) || empty(trim($_POST['category']))) {
        $error = "Titel, Autor und Kategorie sind Pflichtfelder.";
    } else {
        // Neues Book-Objekt erstellen mit bereinigten Daten
        $book = new Book(
            trim($_POST['title']),                                                    // String: Titel (bereinigt)
            trim($_POST['author']),                                                   // String: Autor (bereinigt)
            trim($_POST['category']),                                                 // String: Kategorie (bereinigt)
            $_POST['year'] !== '' ? (int)$_POST['year'] : null,                     // ?int: Jahr oder null
            $_POST['publisher'] !== '' ? trim($_POST['publisher']) : null            // ?string: Verlag oder null
        );
        // Active Record Pattern: Objekt speichert sich selbst
        $book->save();
        // PRG-Pattern: Redirect nach erfolgreichem Speichern
        header('Location: index.php');
        exit;
    }
}

/**
 * CRUD-Operation: UPDATE (Buch bearbeiten)
 * 
 * Verarbeitet POST-Requests vom "Buch bearbeiten"-Formular.
 * Lädt existierendes Buch, aktualisiert Eigenschaften und speichert.
 */
if (isset($_POST['edit_book'])) {
    // Existierendes Buch anhand ID laden
    $book = Book::get((int)$_POST['id']);
    if ($book) {
        // Objekt-Eigenschaften mit Setter-Methoden aktualisieren
        $book->setTitle($_POST['title']);
        $book->setAuthor($_POST['author']);
        $book->setCategory($_POST['category']);
        $book->setYear($_POST['year'] !== '' ? (int)$_POST['year'] : null);
        $book->setPublisher($_POST['publisher'] !== '' ? $_POST['publisher'] : null);
        // Active Record Pattern: Objekt aktualisiert sich selbst in DB
        $book->save();
    }
    // PRG-Pattern: Redirect nach erfolgreichem Update
    header('Location: index.php');
    exit;
}

/**
 * CRUD-Operation: DELETE (Buch löschen)
 * 
 * Verarbeitet GET-Requests mit delete-Parameter.
 * Lädt Buch anhand ID und löscht es aus der Datenbank.
 * Verwendet GET statt POST für einfache Verlinkung (mit JavaScript-Bestätigung).
 */
if (isset($_GET['delete'])) {
    // Buch anhand der übertragenen ID laden und löschen
    $book = Book::get((int)$_GET['delete']);
    if ($book) {
        // Active Record Pattern: Objekt löscht sich selbst aus DB
        $book->delete();
    }
    // PRG-Pattern: Redirect nach erfolgreichem Löschen
    header('Location: index.php');
    exit;
}

/**
 * CRUD-Operation: READ (Bücher laden und filtern)
 * 
 * Lädt Bücher aus der Datenbank mit optionaler Filterung.
 * Unterstützt Filter nach Autor und Kategorie über GET-Parameter.
 * Standardmäßig werden alle Bücher geladen.
 */
// Alle Bücher als Standarddatensatz laden
$books = Book::getAll();

// GET-Parameter für Filter auslesen (mit Fallback auf leeren String)
$filterAuthor = $_GET['author'] ?? '';
$filterCategory = $_GET['category'] ?? '';

// Filter anwenden falls entsprechende Parameter gesetzt sind
if ($filterAuthor !== '') {
    // Nach spezifischem Autor filtern
    $books = Book::getByAuthor($filterAuthor);
}
if ($filterCategory !== '') {
    // Nach spezifischer Kategorie filtern 
    $books = Book::getByCategory($filterCategory);
}
// Hinweis: Bei beiden Filtern wird der zweite den ersten überschreiben
// Eine Kombinationsfilterung ist mit dieser Implementierung nicht möglich
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>WebBiblio - Buchverwaltung</title>
    <!-- Einfaches CSS für Tabellen-Styling -->
    <style>
        /* Tabellen-Layout mit Rahmen */
        table {
            border-collapse: collapse;
            /* Doppelte Rahmen vermeiden */
            width: 100%;
            /* Volle Breite nutzen */
        }

        /* Zellen-Styling für Kopf und Daten */
        th,
        td {
            border: 1px solid #ccc;
            /* Grauer Rahmen */
            padding: 8px;
            /* Innenabstand */
        }

        /* Kopfzeilen-Hervorhebung */
        th {
            background: #f0f0f0;
            /* Hellgrauer Hintergrund */
        }
    </style>
</head>

<body>
    <!-- Hauptüberschrift der Anwendung -->
    <h1>Bücherliste</h1>

    <!-- Fehleranzeige (nur bei Input-Validierungsfehlern) -->
    <?php if (isset($error)): ?>
        <div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Filter-Formular für Autor und Kategorie -->
    <form method="get">
        <input type="text" name="author" placeholder="Nach Autor filtern" value="<?= htmlspecialchars($filterAuthor) ?>">
        <input type="text" name="category" placeholder="Nach Kategorie filtern" value="<?= htmlspecialchars($filterCategory) ?>">
        <button type="submit">Filtern</button>
        <a href="index.php">Filter zurücksetzen</a>
    </form>
    <br><br>
    <!-- Konditionale Formular-Anzeige basierend auf GET-Parametern -->
    <?php if (isset($_GET['add'])): ?>
        <!-- FORMULAR: Neues Buch hinzufügen -->
        <h2>Neues Buch hinzufügen</h2>
        <form method="post">
            <!-- Pflichtfelder (required) -->
            <input type="text" name="title" placeholder="Titel" required>
            <input type="text" name="author" placeholder="Autor" required>
            <input type="text" name="category" placeholder="Kategorie" required>
            <!-- Optionale Felder -->
            <input type="number" name="year" placeholder="Jahr">
            <input type="text" name="publisher" placeholder="Verlag">
            <!-- Submit-Button mit eindeutigem Namen für POST-Verarbeitung -->
            <button type="submit" name="add_book">Speichern</button>
            <a href="index.php">Abbrechen</a>
        </form>
        <br>
    <?php elseif (isset($_GET['edit'])): ?>
        <!-- FORMULAR: Existierendes Buch bearbeiten -->
        <?php $editBook = Book::get($_GET['edit']); ?>
        <?php if ($editBook): ?>
            <h2>Buch bearbeiten</h2>
            <form method="post">
                <!-- Hidden Field: Buch-ID für Update-Operation -->
                <input type="hidden" name="id" value="<?= $editBook->getId() ?>">
                <!-- Vorausgefüllte Felder mit aktuellen Werten (XSS-geschützt) -->
                <input type="text" name="title" placeholder="Titel" value="<?= htmlspecialchars($editBook->getTitle()) ?>" required>
                <input type="text" name="author" placeholder="Autor" value="<?= htmlspecialchars($editBook->getAuthor()) ?>" required>
                <input type="text" name="category" placeholder="Kategorie" value="<?= htmlspecialchars($editBook->getCategory()) ?>" required>
                <input type="number" name="year" placeholder="Jahr" value="<?= $editBook->getYear() ?>">
                <input type="text" name="publisher" placeholder="Verlag" value="<?= htmlspecialchars($editBook->getPublisher() ?? '') ?>">
                <!-- Submit-Button mit eindeutigem Namen für POST-Verarbeitung -->
                <button type="submit" name="edit_book">Aktualisieren</button>
                <a href="index.php">Abbrechen</a>
            </form>
            <br>
        <?php endif; ?>
    <?php else: ?>
        <!-- STANDARD-ANSICHT: Nur "Hinzufügen"-Button anzeigen -->
        <div style="margin-bottom: 20px;">
            <a href="?add=1" class="btn btn-success"> Neues Buch hinzufügen</a>
        </div>
    <?php endif; ?>
    <!-- HAUPTTABELLE: Bücherliste mit allen CRUD-Aktionen -->
    <table>
        <!-- Tabellenkopf mit allen Spalten -->
        <tr>
            <th>ID</th> <!-- Eindeutige Datenbank-ID -->
            <th>Titel</th> <!-- Buchtitel (Pflichtfeld) -->
            <th>Autor</th> <!-- Autor (Pflichtfeld) -->
            <th>Kategorie</th> <!-- Kategorie (Pflichtfeld) -->
            <th>Jahr</th> <!-- Erscheinungsjahr (optional) -->
            <th>Verlag</th> <!-- Verlag (optional) -->
            <th>Aktionen</th> <!-- Edit/Delete-Links -->
        </tr>

        <!-- Dynamische Tabellenzeilen: Schleife über alle geladenen Bücher -->
        <?php foreach ($books as $book): ?>
            <tr>
                <!-- Alle Datenfelder XSS-geschützt ausgeben -->
                <td><?= $book->getId() ?></td> <!-- ID: Integer, kein XSS-Schutz nötig -->
                <td><?= htmlspecialchars($book->getTitle()) ?></td> <!-- Titel: XSS-Schutz -->
                <td><?= htmlspecialchars($book->getAuthor()) ?></td> <!-- Autor: XSS-Schutz -->
                <td><?= htmlspecialchars($book->getCategory()) ?></td> <!-- Kategorie: XSS-Schutz -->
                <td><?= $book->getYear() ?></td> <!-- Jahr: Integer oder null -->
                <td><?= htmlspecialchars($book->getPublisher() ?? '') ?></td> <!-- Verlag: XSS-Schutz, null-safe -->
                <td>
                    <!-- CRUD-Aktionen: Bearbeiten und Löschen -->
                    <a href="?edit=<?= $book->getId() ?>">Bearbeiten</a> <!-- Edit: GET-Parameter -->
                    <!-- Delete: JavaScript-Bestätigung vor Ausführung -->
                    <a href="?delete=<?= $book->getId() ?>" onclick="return confirm('Löschen?');">Löschen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>