<?php
require_once __DIR__ . '/../../app.php'; // Adjust the path based on your directory structure
use genescu\components\MySQLDatabase;

class SalesManager
{
    private $dbConnection;

    private $table = 'sale';

    public function __construct(MySQLDatabase $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function importSalesData($jsonFile): void
    {
        if (!file_exists($jsonFile)) {
            throw new \InvalidArgumentException(sprintf("%s not found", $jsonFile));
        }

        $jsonData = file_get_contents($jsonFile);
        $sales = json_decode($jsonData, true);

        if ($sales === null) {
            throw new \InvalidArgumentException("Failed to decode JSON.");
        }

        // Validate the format of each sale item
        foreach ($sales as $sale) {
            $this->validateSaleItem($sale);
        }

        try {
            $this->dbConnection->setTable($this->table);
            $this->dbConnection->beginTransaction();

            foreach ($sales as $sale) {
                $this->dbConnection->insert($sale);
            }
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    private function validateSaleItem(array $sale): void
    {
        // Define the expected keys and their value types
        $expectedKeys = [
            'sale_id' => 'string',
            'customer_name' => 'string',
            'customer_mail' => 'string',
            'product_id' => 'integer',
            'product_name' => 'string',
            'product_price' => 'string', //float
            'sale_date' => 'string' // Assuming 'sale_date' is a string in ISO 8601 format
        ];

        // Validate the keys and value types
        foreach ($expectedKeys as $key => $expectedType) {
            if (!array_key_exists($key, $sale)) {
                throw new \InvalidArgumentException("Key '$key' is missing in the JSON data.");
            }
            $value = $sale[$key];
            $actualType = gettype($value);

            // Perform type checking
            if ($actualType !== $expectedType) {
                throw new \InvalidArgumentException("Value of key '$key' is expected to be of type '$expectedType', '$actualType' given.");
            }

            // Additional checks for specific types
            if ($expectedType === 'float' && !is_float($value)) {
                throw new \InvalidArgumentException("Value of key '$key' is expected to be a float.");
            }
        }
    }
}


try {
    $dbManager = new SalesManager(MySQLDatabase::getInstance());
    $dbManager->importSalesData(__DIR__ . '/../imports/sales.json');
    echo "Data imported successfully.\n";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}



