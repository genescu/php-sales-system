<?php declare(strict_types=1);

use genescu\components\factories\SalesTableFactory;

require_once __DIR__ . '/../app.php';

// Initialize the SalesTableFactory
$salesTableFactory = SalesTableFactory::getInstance();
$salesTable = $salesTableFactory->getSalesTable();

// Initialize the filter array
$filter = [];

// Check for customer filter
if (isset($_GET['customer'])) {
    $filter['customer_name']['LIKE%'] = $_GET['customer'];
}

// Check for product filter
if (isset($_GET['product'])) {
    $filter['product_name']['LIKE%'] = $_GET['product'];
}

// Check for minimum price filter
if (isset($_GET['price_min']) && (int)$_GET['price_min'] > 0) {
    $filter['product_price']['>='] = (int)$_GET['price_min'];
}

// Check for maximum price filter
if (isset($_GET['price_max']) && (int)$_GET['price_max'] > 0) {
    $filter['product_price']['<='] = (int)$_GET['price_max'];
}

$filteredSales = $salesTable->filter($filter);

// Set Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON encoded filtered data
echo json_encode($filteredSales);
