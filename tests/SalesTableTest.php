<?php

use genescu\components\models\SalesTable;
use PHPUnit\Framework\TestCase;

class SalesTableTest extends TestCase
{
    protected $salesTable;

    protected function setUp(): void
    {
        // Mock the MySQLDatabase object
        $mockDatabase = $this->getMockBuilder('genescu\components\MySQLDatabase')
            ->disableOriginalConstructor()
            ->getMock();

        // Create an instance of SalesTable with the mock database object
        $this->salesTable = new SalesTable($mockDatabase);
    }

    public function testFilterMethodReturnsArray()
    {
        // Define some sample criteria
        $criteria = [
            'customer_name' => 'John Doe',
            'product_name' => 'Product A'
        ];

        // Call the filter method
        $result = $this->salesTable->filter($criteria);

        // Assert that the result is an array
        $this->assertIsArray($result);
    }

    public function testFilterMethodWithNoCriteria()
    {
        // Call the filter method with no criteria
        $result = $this->salesTable->filter([]);

        // Assert that the result is an array
        $this->assertIsArray($result);

        // Assert that the result contains at least one element
        $this->assertNotEmpty($result);
    }

    public function testFilterMethodReturnsEmptyArray()
    {
        // Define some sample criteria that do not match any records
        $criteria = [
            'customer_name' => 'Nonexistent Customer',
            'product_name' => 'Nonexistent Product'
        ];

        // Call the filter method
        $result = $this->salesTable->filter($criteria);

        // Assert that the result is an empty array
        $this->assertEmpty($result);
    }
}
