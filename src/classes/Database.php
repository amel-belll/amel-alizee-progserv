<?php

class Database implements DatabaseInterface {

    const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../config/database.ini';

    private $pdo;

    public function __construct() {

        // Documentation : https://www.php.net/manual/fr/function.parse-ini-file.php

        $config = parse_ini_file(self::DATABASE_CONFIGURATION_FILE, true);

        if (!$config) {

            throw new Exception("Erreur lors de la lecture du fichier de configuration : " . self::DATABASE_CONFIGURATION_FILE);
        }

        $host = $config['host'];

        $port = $config['port'];

        $database = $config['database'];

        $username = $config['username'];

        $password = $config['password'];

        // Documentation :

        //   - https://www.php.net/manual/fr/pdo.connections.php

        //   - https://www.php.net/manual/fr/ref.pdo-mysql.connection.php

        $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    }

    public function getPdo(): PDO {

        return $this->pdo;
    }
}
