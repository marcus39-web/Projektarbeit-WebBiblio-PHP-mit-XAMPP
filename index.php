<?php
// index.php
// Hauptseite für die Buchverwaltung

// Autoloading für Klassen (PSR-4-ähnlich, einfach)
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/Components/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Fallback für Dateien im Root-Verzeichnis
        $file = __DIR__ . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// --- Buch hinzufügen ---
if (isset($_POST['add_book'])) {
    // Input-Validierung
    if (empty(trim($_POST['title'])) || empty(trim($_POST['author'])) || empty(trim($_POST['category']))) {
        $error = "Titel, Autor und Kategorie sind Pflichtfelder.";
    } else {
        // Neues Buch anlegen
        $book = new Book(
            trim($_POST['title']),
            trim($_POST['author']),
            trim($_POST['category']),
            $_POST['year'] !== '' ? (int)$_POST['year'] : null,
            $_POST['publisher'] !== '' ? trim($_POST['publisher']) : null
        );
        $book->save();
        header('Location: index.php');
        exit;
    }
}

// --- Buch bearbeiten ---
if (isset($_POST['edit_book'])) {
    // Buchdaten aktualisieren
    $book = Book::get((int)$_POST['id']);
    if ($book) {
        $book->setTitle($_POST['title']);
        $book->setAuthor($_POST['author']);
        $book->setCategory($_POST['category']);
        $book->setYear($_POST['year'] !== '' ? (int)$_POST['year'] : null);
        $book->setPublisher($_POST['publisher'] !== '' ? $_POST['publisher'] : null);
        $book->save();
    }
    header('Location: index.php');
    exit;
}

// --- Buch löschen ---
if (isset($_GET['delete'])) {
    // Buch anhand der ID löschen
    $book = Book::get((int)$_GET['delete']);
    if ($book) {
        $book->delete();
    }
    header('Location: index.php');
    exit;
}

// Bücher laden und ggf. filtern
$books = Book::getAll();
$filterAuthor = $_GET['author'] ?? '';
$filterCategory = $_GET['category'] ?? '';
if ($filterAuthor !== '') {
    $books = Book::getByAuthor($filterAuthor);
}
if ($filterCategory !== '') {
    $books = Book::getByCategory($filterCategory);
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>WebBiblio - Buchverwaltung</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f0f0f0;
        }
    </style>
</head>

<body>
    <h1>Bücherliste</h1>
    <?php if (isset($error)): ?>
        <div style="color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="get">
        <input type="text" name="author" placeholder="Nach Autor filtern" value="<?= htmlspecialchars($filterAuthor) ?>">
        <input type="text" name="category" placeholder="Nach Kategorie filtern" value="<?= htmlspecialchars($filterCategory) ?>">
        <button type="submit">Filtern</button>
        <a href="index.php">Filter zurücksetzen</a>
    </form>
    <br><br>
    <?php if (isset($_GET['add'])): ?>
        <h2>Neues Buch hinzufügen</h2>
        <form method="post">
            <input type="text" name="title" placeholder="Titel" required>
            <input type="text" name="author" placeholder="Autor" required>
            <input type="text" name="category" placeholder="Kategorie" required>
            <input type="number" name="year" placeholder="Jahr">
            <input type="text" name="publisher" placeholder="Verlag">
            <button type="submit" name="add_book">Speichern</button>
            <a href="index.php">Abbrechen</a>
        </form>
        <br>
    <?php elseif (isset($_GET['edit'])): ?>
        <?php $editBook = Book::get($_GET['edit']); ?>
        <?php if ($editBook): ?>
            <h2>Buch bearbeiten</h2>
            <form method="post">
                <input type="hidden" name="id" value="<?= $editBook->getId() ?>">
                <input type="text" name="title" placeholder="Titel" value="<?= htmlspecialchars($editBook->getTitle()) ?>" required>
                <input type="text" name="author" placeholder="Autor" value="<?= htmlspecialchars($editBook->getAuthor()) ?>" required>
                <input type="text" name="category" placeholder="Kategorie" value="<?= htmlspecialchars($editBook->getCategory()) ?>" required>
                <input type="number" name="year" placeholder="Jahr" value="<?= $editBook->getYear() ?>">
                <input type="text" name="publisher" placeholder="Verlag" value="<?= htmlspecialchars($editBook->getPublisher() ?? '') ?>">
                <button type="submit" name="edit_book">Aktualisieren</button>
                <a href="index.php">Abbrechen</a>
            </form>
            <br>
        <?php endif; ?>
    <?php else: ?>
        <div style="margin-bottom: 20px;">
            <a href="?add=1" class="btn btn-success"> Neues Buch hinzufügen</a>
            <a href="Components/001_create_books_table.php" class="btn" style="background-color: #9b59b6;"> Tabelle erstellen</a>
            <a href="Components/BookSeeder.php" class="btn" style="background-color: #e67e22;"> Testdaten laden (50 Bücher)</a>
        </div>
    <?php endif; ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Autor</th>
            <th>Kategorie</th>
            <th>Jahr</th>
            <th>Verlag</th>
            <th>Aktionen</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= $book->getId() ?></td>
                <td><?= htmlspecialchars($book->getTitle()) ?></td>
                <td><?= htmlspecialchars($book->getAuthor()) ?></td>
                <td><?= htmlspecialchars($book->getCategory()) ?></td>
                <td><?= $book->getYear() ?></td>
                <td><?= htmlspecialchars($book->getPublisher() ?? '') ?></td>
                <td>
                    <a href="?edit=<?= $book->getId() ?>">Bearbeiten</a>
                    <a href="?delete=<?= $book->getId() ?>" onclick="return confirm('Löschen?');">Löschen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>