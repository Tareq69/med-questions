// console.log("Hello")

document.getElementById('filterForm').addEventListener('submit', function(e){
    console.log("Form")
    e.preventDefault();
    filterProducts();
});

// 
function filterProducts() {
    var formData = new FormData(document.getElementById('filterForm'));

    // Making an AJAX call to server-side script
    fetch('/product-filter', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Handling the response data and update view
        let products = data.products;
        console.log(products)
        updateTable(products);
    })
    .catch(error => {
        console.log(error);
    });
}
// 

function updateTable(products) {
    // Clearing the current product table
    var productTable = document.querySelector('table');
    productTable.innerHTML = `
    <tr>
        <th>Title</th>
        <th width="150px">Variant</th>
        <th>Price</th>
        <th width="150px">Created at</th>
    </tr>
    `;

    if (products.length < 1) {
        productTable.innerHTML += `
            <tr>
                <td colspan="4">No products found</td>
            </tr>
        `;
    } else {
        // Append the filtered products to the table
        products.forEach(function(product) {
            var date = new Date(product.created_at);
            var options = { day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
            var created_at = date.toLocaleString('en-US', options);
            productTable.innerHTML += `
                <tr>
                    <td>${product.title}</td>
                    <td>${product.variant}</td>
                    <td>${product.price}</td>
                    <td>${created_at}</td>
                </tr>
            `;
        });
    }
}







function fetchOriginalProducts() {
    // make the GET request to the server
    return fetch('/product')
        .then(response => response.json())
        .then(data => {
            // the data returned by the server is available in the 'data.data' variable
            return data.data;
        })
        .catch(error => {
            // handle any errors that occur during the fetch
            console.error('Error fetching original products:', error);
        });
}


