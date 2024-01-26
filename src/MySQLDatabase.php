<?php
namespace genescu\components;
use genescu\components\interfaces\DatabaseInterface;
use genescu\components\interfaces\TableInterface;
use PDO;

class MySQLDatabase implements DatabaseInterface, TableInterface
{
    private static $instance;
    protected $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    private $table;

    public function __construct($host, $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            $config = [
                'host' => DB_HOST,
                'username' => DB_USER,
                'password' => DB_PASS,
                'database' => DB_NAME
            ];
            self::$instance = new MySQLDatabase($config['host'], $config['username'], $config['password'], $config['database']);
        }

        return self::$instance;
    }

    public function connect(): void
    {
        $dsn = "mysql:host={$this->host};charset=utf8mb4";
        $options = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // You may need to adjust this based on your server configuration
        ];

        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            $options[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            $dsn .= ";charset=utf8mb4";
        } else {
            $options[\PDO::MYSQL_ATTR_DEFAULT_AUTH] = \PDO::MYSQL_ATTR_AUTH_PLUGIN;
        }

        $this->connection = new \PDO($dsn, $this->username, $this->password, $options);
    }

    public function useDatabase(): void
    {
        $this->connect();

        $sql = "USE {$this->database}";
        $this->connection->exec($sql);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function createDatabase(): void
    {
        $this->connect();
        $sql = "CREATE DATABASE IF NOT EXISTS {$this->database} DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $this->getConnection()->exec($sql);
    }

    public function beginTransaction()
    {
        if (is_null($this->getConnection())) {
            $this->useDatabase();
        }

        if (!$this->getConnection()->inTransaction()) {
            $this->getConnection()->beginTransaction();
        }
    }

    public function commit()
    {
        if (is_null($this->getConnection())) {
            $this->useDatabase();
        }

        if ($this->getConnection()->inTransaction()) {
            $this->getConnection()->commit();
        }
    }

    public function rollBack()
    {
        if (is_null($this->getConnection())) {
            $this->useDatabase();
        }

        if ($this->getConnection()->inTransaction()) {
            $this->getConnection()->rollBack();
        }
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table): void
    {
        $this->table = $table;
    }

    public function insert(array $data): void
    {
        $this->useDatabase();

        // Get the table name from the database connection
        $tableName = $this->getTable();

        // Prepare the SQL query
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `$tableName` ($columns) VALUES ($values)";

        // Prepare and execute the statement
        $statement = $this->connection->prepare($sql);
        $statement->execute(array_values($data));
    }


    public function filter(array $criteria): array
    {
        $conditions = [];
        $values = [];
        foreach ($criteria as $column => $columnConditions) {
            foreach ($columnConditions as $operator => $value) {
                if ($operator === 'LIKE%') {
                    $conditions[] = "{$column} LIKE ?";
                    $values[] = $value . '%';
                } else {
                    $conditions[] = "{$column} {$operator} ?";
                    $values[] = $value;
                }
            }
        }
        if (empty($conditions)) {
            return [];
        }
        $tableName = $this->table;
        $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        $sql = "SELECT * FROM `{$tableName}` {$whereClause}";
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($values);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


}

