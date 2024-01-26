<?php declare(strict_types=1);
require_once __DIR__ . '/../../app.php'; // Adjust the path based on your directory structure
use genescu\components\MySQLDatabase;

class DatabaseManager
{
    private $dbConnection;

    public function __construct(MySQLDatabase $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function createDatabase(): void
    {
        $this->dbConnection->createDatabase();
    }

    public function createProductTable(): void
    {
        $this->dbConnection->useDatabase();

        $sql = "CREATE TABLE IF NOT EXISTS product (
            product_id INT AUTO_INCREMENT PRIMARY KEY,
            product_name VARCHAR(255) NOT NULL,
            product_price DECIMAL(10, 2) NOT NULL
        )";

        $this->dbConnection->getConnection()->exec($sql);
    }

    public function createCustomerTable(): void
    {
        $this->dbConnection->useDatabase();

        $sql = "CREATE TABLE IF NOT EXISTS customer (
            customer_id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(255) NOT NULL,
            customer_mail VARCHAR(255) NOT NULL
        )";

        $this->dbConnection->getConnection()->exec($sql);
    }

    public function createSaleTable(): void
    {
        $this->dbConnection->useDatabase();

        $sql = "CREATE TABLE IF NOT EXISTS sale (
            sale_id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT NULL,
            customer_name VARCHAR(255) NOT NULL,
            customer_mail VARCHAR(255) NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            product_price DECIMAL(10, 2) NOT NULL,
            sale_date DATETIME NOT NULL
        )";

        $this->dbConnection->getConnection()->exec($sql);
    }
}

try {
    // easy to switch between development and production environments.
    $dbManager = new DatabaseManager(MySQLDatabase::getInstance());
    $dbManager->createDatabase();
    $dbManager->createProductTable();
    $dbManager->createCustomerTable();
    $dbManager->createSaleTable();

    echo "MySQL schema and table created successfully.\n";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
