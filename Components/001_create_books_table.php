<?php
// 001_create_books_table.php
// MySQL-Version (mit DatabaseSingleton.php)

echo "<!DOCTYPE html>
<html lang='de'>
<head>
    <meta charset='UTF-8'>
    <title>MySQL Tabelle erstellen</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>MySQL Datenbank erstellen</h1>";

try {
    require_once 'DatabaseSingleton.php';

    $db = DatabaseSingleton::getInstance();
    $pdo = $db->getConnection();

    echo "<div class='success'>MySQL-Verbindung erfolgreich!</div>";

    // Tabelle erstellen
    $sql = "CREATE TABLE IF NOT EXISTS books (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        category VARCHAR(255) NOT NULL,
        year YEAR,
        publisher VARCHAR(255)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "<div class='success'>Tabelle 'books' wurde erfolgreich erstellt.</div>";

    // Tabellenstruktur anzeigen
    $stmt = $pdo->query("DESCRIBE books");
    echo "<h3>Tabellenstruktur:</h3>";
    echo "<table>";
    echo "<tr><th>Feld</th><th>Typ</th><th>Nicht NULL</th><th>Standard</th><th>Schlüssel</th></tr>";
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . ($row['Null'] == 'NO' ? 'Ja' : 'Nein') . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . ($row['Key'] ? 'Ja' : 'Nein') . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Anzahl der Datensätze prüfen
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM books");
    $bookCount = $stmt->fetch()['count'];
    echo "<p><strong>Anzahl Bücher in der Tabelle:</strong> $bookCount</p>";

    if ($bookCount == 0) {
        echo "<div class='info'>";
        echo "<p>Die Tabelle ist leer. Sie können jetzt:</p>";
        echo "<ul>";
        echo "<li><a href='../index.php'>Zur Hauptanwendung gehen</a> und manuell Bücher hinzufügen</li>";
        echo "<li><a href='BookSeeder.php'>Testdaten laden</a> (30 Beispielbücher)</li>";
        echo "</ul>";
        echo "</div>";
    }

    echo "<div class='success'>";
    echo "<h3>Alles bereit!</h3>";
    echo "<p>Ihre Buchverwaltung ist jetzt einsatzbereit mit MySQL als Datenbank.</p>";
    echo "<p><strong>Vorteile von MySQL:</strong></p>";
    echo "<ul>";
    echo "<li>Leistungsstark und weit verbreitet</li>";
    echo "<li>Unterstützt große Datenmengen</li>";
    echo "<li>Robuste Sicherheitsfunktionen</li>";
    echo "<li>Erfüllt alle Projektanforderungen</li>";
    echo "</ul>";
    echo "</div>";
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<h3>Fehler:</h3>";
    echo "<p><strong>Fehlermeldung:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<br><p><a href='../index.php' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>🚀 Zur Hauptanwendung</a></p>";
echo "</div></body></html>";
