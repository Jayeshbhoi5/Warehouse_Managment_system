// Calculate total amount based on cost and discount
document.getElementById('inventory-form').addEventListener('input', function() {
    let cost = parseFloat(document.getElementById('cost').value) || 0;
    let quantity = parseInt(document.getElementById('quantity').value) || 0;
    let discount = parseFloat(document.getElementById('discount').value) || 0;
    
    let totalAmount = cost * quantity * (1 - (discount / 100));
    document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
});
