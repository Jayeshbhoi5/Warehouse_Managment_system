// Update the existing script with these changes
function toggleMenu() {
    document.getElementById('sideMenu').classList.toggle('active');
    document.querySelector('.main-content').classList.toggle('shifted');
    
    // Add a small delay to allow the transition to complete
    setTimeout(() => {
        const charts = Chart.instances;
        charts.forEach(chart => {
            chart.resize();
        });
    }, 300);
}

// Update the chart initialization
function initializeCharts() {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        animation: {
            duration: 300 // Match the CSS transition duration
        }
    };

    // Inventory Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(inventoryCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Inventory Levels',
                data: [4000, 3000, 2000, 2780, 1890, 2390],
                backgroundColor: '#8884d8'
            }]
        },
        options: commonOptions
    });

    // Order Chart
    const orderCtx = document.getElementById('orderChart').getContext('2d');
    new Chart(orderCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Order Trends',
                data: [2400, 1398, 9800, 3908, 4800, 3800],
                borderColor: '#82ca9d',
                tension: 0.1
            }]
        },
        options: commonOptions
    });
}

// Add window resize handler
window.addEventListener('resize', () => {
    const charts = Chart.instances;
    charts.forEach(chart => {
        chart.resize();
    });
});
// Add this to script1.js

// Add this to script1.js

// Existing chart code remains the same...

// Existing chart code remains the same...

function addOrderRow() {
    const tbody = document.getElementById('orderItems');
    const newRow = document.createElement('tr');
    const rowCount = tbody.children.length + 1;
    
    newRow.innerHTML = `
        <td>${rowCount}</td>
        <td><input type="text" class="product-name" placeholder="Enter product name"></td>
        <td><input type="number" class="order-qty" onchange="calculateItemTotal(this)" min="1"></td>
        <td><input type="number" class="price" onchange="calculateItemTotal(this)"></td>
        <td><input type="text" class="item-total" readonly></td>
    `;
    
    tbody.appendChild(newRow);
}

function removeOrderRow() {
    const tbody = document.getElementById('orderItems');
    if (tbody.children.length > 1) {
        tbody.removeChild(tbody.lastChild);
        calculateSubTotal();
    }
}

function calculateItemTotal(input) {
    const row = input.closest('tr');
    const qty = parseFloat(row.querySelector('.order-qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const total = qty * price;
    row.querySelector('.item-total').value = total.toFixed(2);
    calculateSubTotal();
}

function calculateSubTotal() {
    const totals = Array.from(document.querySelectorAll('.item-total'))
        .map(input => parseFloat(input.value) || 0);
    const subTotal = totals.reduce((sum, total) => sum + total, 0);
    document.getElementById('subTotal').value = subTotal.toFixed(2);
    calculateNetTotal();
}

function calculateNetTotal() {
    const subTotal = parseFloat(document.getElementById('subTotal').value) || 0;
    const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
    const discountAmount = (subTotal * discountPercent) / 100;
    const netTotal = subTotal - discountAmount;
    
    document.getElementById('discountAmount').value = discountAmount.toFixed(2);
    document.getElementById('netTotal').value = netTotal.toFixed(2);
}

function placeOrder() {
    const customerName = document.getElementById('customerName').value;
    if (!customerName) {
        alert('Please enter customer name');
        return;
    }

    const orderData = {
        customerName: customerName,
        orderDate: document.getElementById('orderDate').value,
        items: Array.from(document.getElementById('orderItems').children).map(row => ({
            productName: row.querySelector('.product-name').value,
            quantity: parseInt(row.querySelector('.order-qty').value),
            price: parseFloat(row.querySelector('.price').value),
            total: parseFloat(row.querySelector('.item-total').value)
        })),
        subTotal: parseFloat(document.getElementById('subTotal').value),
        discountPercent: parseFloat(document.getElementById('discountPercent').value),
        discountAmount: parseFloat(document.getElementById('discountAmount').value),
        netTotal: parseFloat(document.getElementById('netTotal').value)
    };

    fetch('save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order saved successfully!');
            window.location.reload();
        } else {
            alert('Error saving order: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error saving order: ' + error.message);
    });
}

// Initialize charts and attach event listeners when document loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    
    // Show dashboard section by default
    showSection('dashboard');
});

function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Show selected section
    document.getElementById(sectionName + 'Section').style.display = 'block';
    
    // Update active menu item
    document.querySelectorAll('.side-menu a').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`.side-menu a[onclick="showSection('${sectionName}')"]`).classList.add('active');
}
function validateOrder() {
    const customerName = document.getElementById('customerName').value.trim();
    if (!customerName) {
        alert('Please enter customer name');
        return false;
    }

    const items = Array.from(document.getElementById('orderItems').children);
    for (let i = 0; i < items.length; i++) {
        const productName = items[i].querySelector('.product-name').value.trim();
        const quantity = items[i].querySelector('.order-qty').value;
        const price = items[i].querySelector('.price').value;

        if (!productName || !quantity || !price) {
            alert(`Please fill in all details for item ${i + 1}`);
            return false;
        }
    }

    return true;
}

function placeOrder() {
    if (!validateOrder()) {
        return;
    }

    // Disable the button and show loading state
    const orderButton = document.querySelector('.place-order-btn');
    orderButton.disabled = true;
    orderButton.classList.add('loading');
    orderButton.textContent = 'Processing...';

    const orderData = {
        customerName: document.getElementById('customerName').value,
        orderDate: document.getElementById('orderDate').value,
        items: Array.from(document.getElementById('orderItems').children).map(row => ({
            productName: row.querySelector('.product-name').value,
            quantity: parseInt(row.querySelector('.order-qty').value),
            price: parseFloat(row.querySelector('.price').value),
            total: parseFloat(row.querySelector('.item-total').value)
        })),
        subTotal: parseFloat(document.getElementById('subTotal').value),
        discountPercent: parseFloat(document.getElementById('discountPercent').value) || 0,
        discountAmount: parseFloat(document.getElementById('discountAmount').value) || 0,
        netTotal: parseFloat(document.getElementById('netTotal').value)
    };

    fetch('save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Order saved successfully!');
            
            // Reset the form
            document.getElementById('customerName').value = '';
            document.getElementById('orderItems').innerHTML = `
                <tr>
                    <td>1</td>
                    <td><input type="text" class="product-name" placeholder="Enter product name"></td>
                    <td><input type="number" class="order-qty" onchange="calculateItemTotal(this)" min="1"></td>
                    <td><input type="number" class="price" onchange="calculateItemTotal(this)"></td>
                    <td><input type="text" class="item-total" readonly></td>
                </tr>
            `;
            document.getElementById('discountPercent').value = '';
            document.getElementById('subTotal').value = '';
            document.getElementById('discountAmount').value = '';
            document.getElementById('netTotal').value = '';
        } else {
            alert('Error saving order: ' + data.error);
        }
    })
    .catch(error => {
        alert('Error saving order: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        orderButton.disabled = false;
        orderButton.classList.remove('loading');
        orderButton.textContent = 'Place Order';
    });
}

// Add this to handle the Enter key in the form
document.querySelector('.order-form-container').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        if (e.target.classList.contains('price') || e.target.classList.contains('order-qty')) {
            calculateItemTotal(e.target);
        }
    }
});