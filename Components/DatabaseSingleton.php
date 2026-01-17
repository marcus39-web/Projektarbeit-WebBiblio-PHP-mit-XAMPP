<?php
// DatabaseSingleton.php
// MySQL-Version

class DatabaseSingleton
{
    private static ?DatabaseSingleton $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $host = 'localhost';
        $port = '3306';
        $dbname = 'library';
        $username = 'root';
        $password = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->connection = new PDO($dsn, $username, $password, $options);
    }

    public static function getInstance(): DatabaseSingleton
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseSingleton();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
?>
