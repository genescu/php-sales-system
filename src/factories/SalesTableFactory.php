<?php declare(strict_types=1);
namespace genescu\components\factories;
use genescu\components\models\SalesTable;
use genescu\components\MySQLDatabase;

class SalesTableFactory
{
    /**
     * @var null
     */
    private static $instance;
    private  $salesTable;

    private function __construct()
    {
        // Private constructor to prevent direct instantiation
        $dbConnection = MySQLDatabase::getInstance();
        $this->salesTable = new SalesTable($dbConnection);
    }

    public static function getInstance(): SalesTableFactory
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getSalesTable(): SalesTable
    {
        return $this->salesTable;
    }
}
