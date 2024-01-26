<?php
require_once __DIR__ . '/../app.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Page</title>
    <!-- Include CSS styles -->
    <link rel='stylesheet' id='sydney-style-css' href='/assets/css/style.css' type='text/css' media='all' />
</head>
<body>
<h1>Filter Page</h1>
<div id="form-filter">
    <label for="customer-filter">Customer:</label>
    <input type="text" id="customer-filter">
    <label for="product-filter">Product:</label>
    <input type="text" id="product-filter">
    <label for="price-filter-min">Price Min:</label>
    <input type="number" id="price-filter-min">
    <label for="price-filter-min">Price Max:</label>
    <input type="number" id="price-filter-max">
    <button onclick="applyFilters()">Apply Filters</button>
</div>
<!-- Table to display filtered results -->
<table id="result-table">
</table>

<script src="assets/js/filter.js"></script>
</body>
</html>

