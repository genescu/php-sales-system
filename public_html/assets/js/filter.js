function applyFilters() {
    // Get filter values
    const customerFilter = document.getElementById('customer-filter').value;
    const productFilter = document.getElementById('product-filter').value;
    const priceMinFilter = document.getElementById('price-filter-min').value;
    const priceMaxFilter = document.getElementById('price-filter-max').value;

    // Send AJAX request to backend with filter values
    const xhr = new XMLHttpRequest();
    const url = 'handler.php';
    const params = `customer=${customerFilter}&product=${productFilter}&price_min=${priceMinFilter}&price_max=${priceMaxFilter}`;
    xhr.open('GET', `${url}?${params}`, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Update table with filtered data
                const responseData = JSON.parse(xhr.responseText);
                updateTable(responseData);
            } else {
                console.error('Error:', xhr.status);
            }
        }
    };

    xhr.send();
}

function updateTable(data) {
    // Update table with filtered data
    const table = document.getElementById('result-table');

    if (!table) {
        console.error('Table element not found');
        return;
    }

    // Clear existing table rows and header
    table.innerHTML = '';

    // Add table headers
    let headersHTML = '<thead><tr>';
    for (const key in data[0]) {
        headersHTML += `<th>${key}</th>`;
    }
    headersHTML += '</tr></thead>';

    // Add table body
    let bodyHTML = '<tbody>';
    data.forEach(item => {
        bodyHTML += '<tr>';
        for (const key in item) {
            bodyHTML += `<td>${item[key]}</td>`;
        }
        bodyHTML += '</tr>';
    });
    bodyHTML += '</tbody>';

    // Calculate total price
    const totalPrice = data.reduce((acc, item) => acc + parseFloat(item.product_price), 0);

    // Add last row for total price
    bodyHTML += `<tfoot><tr><td colspan="${Object.keys(data[0]).length}">Total Price: ${totalPrice.toFixed(2)} EUR</td></tr></tfoot>`;

    // Set table HTML
    table.innerHTML = headersHTML + bodyHTML;
}
