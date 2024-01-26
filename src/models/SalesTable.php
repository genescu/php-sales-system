<?php declare(strict_types=1);
namespace genescu\components\models;
use genescu\components\MySQLDatabase;

class SalesTable
{
    private $dbConnection;
    private $tableName = 'sale';

    public function __construct(MySQLDatabase $dbConnection)
    {
        $this->dbConnection = $dbConnection;
        $this->dbConnection->useDatabase();
        $this->dbConnection->setTable($this->tableName);
    }

    public function filter(array $criteria): array
    {
        return $this->getFilteredSales($criteria);
    }

    private function getFilteredSales(array $criteria): array
    {
        $filteredSales = $this->dbConnection->filter($criteria);
        return is_array($filteredSales) ? $filteredSales : [];
    }
}
