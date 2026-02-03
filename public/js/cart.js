let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(productName, price, button) {
    cart.push({ 
        name: productName, 
        price: price,
        quantity: 1,
        id: Date.now()
    });
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    
    // Ripple effect
    button.classList.add('ripple-active');
    setTimeout(() => button.classList.remove('ripple-active'), 600);
    
    // Toast notification
    showToast(`✓ ${productName} added to cart!`, 'success');
}

function updateCartCount() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = cart.length;
        cartCount.classList.add('badge-pulse');
        setTimeout(() => cartCount.classList.remove('badge-pulse'), 300);
    }
}

function displayCartItems() {
    const cartItemsDiv = document.getElementById('cart-items');
    if (!cartItemsDiv) return;

    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div class="empty-cart">
                <p>🛒 Your cart is empty</p>
                <a href="products.html" class="btn btn-primary">Continue Shopping</a>
            </div>
        `;
        updateOrderSummary();
        return;
    }

    cartItemsDiv.innerHTML = '';
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        const itemDiv = document.createElement('div');
        itemDiv.className = 'cart-item';
        itemDiv.innerHTML = `
            <div class="cart-item-image">📦</div>
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">₹${item.price}/kg</div>
                <div class="cart-item-specs">Grade: Premium | Certified</div>
            </div>
            <div class="cart-item-quantity">
                <button onclick="decreaseQuantity(${index})">−</button>
                <input type="number" value="${item.quantity}" readonly>
                <button onclick="increaseQuantity(${index})">+</button>
            </div>
            <div class="cart-item-total">₹${itemTotal.toFixed(2)}</div>
            <button class="cart-item-remove" onclick="removeFromCart(${index})" title="Remove item">×</button>
        `;
        cartItemsDiv.appendChild(itemDiv);
    });
    
    updateOrderSummary();
}

function increaseQuantity(index) {
    cart[index].quantity++;
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCartItems();
}

function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCartItems();
    }
}

function removeFromCart(index) {
    const itemName = cart[index].name;
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    displayCartItems();
    showToast(`✕ ${itemName} removed from cart`, 'info');
}

function updateOrderSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = subtotal > 5000 ? 0 : 200;
    const tax = (subtotal * 0.18);
    const total = subtotal + shipping + tax;
    
    document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
    document.getElementById('shipping').textContent = shipping === 0 ? '🎉 FREE' : `₹${shipping}`;
    document.getElementById('tax').textContent = `₹${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `₹${total.toFixed(2)}`;
}

function generateInvoice(withGST = true) {
    if (cart.length === 0) {
        showToast('Cart is empty!', 'error');
        return;
    }

    const formData = {
        name: document.getElementById('name')?.value,
        email: document.getElementById('email')?.value,
        phone: document.getElementById('phone')?.value,
        company: document.getElementById('company')?.value,
        address: document.getElementById('address')?.value,
        city: document.getElementById('city')?.value,
        state: document.getElementById('state')?.value,
        pincode: document.getElementById('pincode')?.value
    };

    if (!formData.name || !formData.email || !formData.address) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = subtotal > 5000 ? 0 : 200;
    const tax = withGST ? (subtotal * 0.18) : 0;
    const total = subtotal + shipping + tax;

    const invoiceData = {
        ...formData,
        items: cart,
        subtotal,
        shipping,
        tax,
        total,
        withGST,
        invoiceNo: 'INV-' + Date.now(),
        date: new Date().toLocaleDateString()
    };

    generatePDF(invoiceData);
}

function generatePDF(invoiceData) {
    // Create invoice HTML
    const invoiceHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Invoice - ${invoiceData.invoiceNo}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { max-width: 900px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { border-bottom: 3px solid #c0392b; padding-bottom: 20px; margin-bottom: 30px; }
                .logo { font-size: 28px; font-weight: 900; color: #1a1a2e; margin-bottom: 10px; }
                .company-info { color: #666; font-size: 12px; }
                .invoice-title { float: right; text-align: right; }
                .invoice-no { font-size: 20px; font-weight: bold; color: #c0392b; margin-bottom: 5px; }
                .invoice-date { color: #666; font-size: 13px; }
                .info-section { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin: 30px 0; padding: 20px 0; border-bottom: 1px solid #e0e0e0; }
                .info-block h4 { color: #c0392b; font-size: 12px; text-transform: uppercase; font-weight: 700; margin-bottom: 8px; }
                .info-block p { color: #333; font-size: 13px; line-height: 1.6; }
                table { width: 100%; border-collapse: collapse; margin: 30px 0; }
                th { background: #f8f9fa; padding: 12px; text-align: left; font-weight: 700; color: #333; border-bottom: 2px solid #e0e0e0; font-size: 12px; text-transform: uppercase; }
                td { padding: 15px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
                .text-right { text-align: right; }
                .totals { margin-top: 30px; }
                .total-row { display: flex; justify-content: flex-end; margin-bottom: 10px; gap: 30px; }
                .total-label { width: 150px; text-align: right; font-weight: 600; color: #333; }
                .total-value { width: 120px; text-align: right; font-weight: 600; }
                .total-final { border-top: 2px solid #c0392b; padding-top: 12px; margin-top: 12px; }
                .total-final .total-label { font-size: 16px; color: #c0392b; }
                .total-final .total-value { font-size: 18px; color: #c0392b; }
                .notes { background: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 30px; font-size: 12px; color: #666; border-left: 3px solid #c0392b; }
                .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 12px; color: #999; }
                .badge { background: linear-gradient(135deg, #c0392b 0%, #a93226 100%); color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="invoice-title">
                        <div class="invoice-no">${invoiceData.invoiceNo}</div>
                        <div class="invoice-date">${invoiceData.date}</div>
                    </div>
                    <div class="logo">Steel & Iron Traders</div>
                    <div class="company-info">Premium Industrial Materials | Est. 2020</div>
                </div>

                <div class="info-section">
                    <div class="info-block">
                        <h4>Bill To</h4>
                        <p>
                            <strong>${invoiceData.name}</strong><br>
                            ${invoiceData.company ? invoiceData.company + '<br>' : ''}
                            ${invoiceData.address}<br>
                            ${invoiceData.city}, ${invoiceData.state} ${invoiceData.pincode}<br>
                            <br>
                            Email: ${invoiceData.email}<br>
                            ${invoiceData.phone ? 'Phone: ' + invoiceData.phone : ''}
                        </p>
                    </div>
                    <div class="info-block">
                        <h4>Invoice Details</h4>
                        <p>
                            <strong>Invoice Number:</strong> ${invoiceData.invoiceNo}<br>
                            <strong>Date:</strong> ${invoiceData.date}<br>
                            <strong>Due Date:</strong> ${new Date(Date.now() + 30*24*60*60*1000).toLocaleDateString()}<br>
                            ${invoiceData.withGST ? '<strong>GST Applicable:</strong> Yes ✓<br>' : '<strong>GST:</strong> Not Applicable<br>'}
                            <strong>Status:</strong> <span class="badge">PENDING</span>
                        </p>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${invoiceData.items.map((item, idx) => `
                            <tr>
                                <td>${item.name}</td>
                                <td class="text-right">₹${item.price}</td>
                                <td class="text-right">${item.quantity} kg</td>
                                <td class="text-right"><strong>₹${(item.price * item.quantity).toFixed(2)}</strong></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <div class="totals">
                    <div class="total-row">
                        <div class="total-label">Subtotal:</div>
                        <div class="total-value">₹${invoiceData.subtotal.toFixed(2)}</div>
                    </div>
                    <div class="total-row">
                        <div class="total-label">Shipping:</div>
                        <div class="total-value">${invoiceData.shipping === 0 ? '🎉 FREE' : '₹' + invoiceData.shipping}</div>
                    </div>
                    ${invoiceData.withGST ? `
                        <div class="total-row">
                            <div class="total-label">GST (18%):</div>
                            <div class="total-value">₹${invoiceData.tax.toFixed(2)}</div>
                        </div>
                    ` : ''}
                    <div class="total-row total-final">
                        <div class="total-label">Total Due:</div>
                        <div class="total-value">₹${invoiceData.total.toFixed(2)}</div>
                    </div>
                </div>

                <div class="notes">
                    <strong>Payment Terms:</strong> Net 30 days from invoice date. Please make payment to the account details provided via email. Thank you for your business!
                </div>

                <div class="footer">
                    <p>Steel & Iron Traders | info@steelirontraders.com | www.steelirontraders.com</p>
                    <p>This is an electronically generated invoice. No signature required.</p>
                </div>
            </div>
        </body>
        </html>
    `;

    // Open in new window for printing
    const printWindow = window.open('', '', 'width=900,height=600');
    printWindow.document.write(invoiceHTML);
    printWindow.document.close();
    
    // Auto-trigger download after a short delay
    setTimeout(() => {
        printWindow.print();
    }, 250);

    showToast('📄 Invoice generated successfully!', 'success');
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.3s ease;
        font-weight: 600;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    displayCartItems();
    
    // Add styles for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .badge-pulse { animation: badgePulse 0.3s ease; }
    `;
    document.head.appendChild(style);
});

if (document.getElementById('cartData')) {
    document.getElementById('cartData').value = localStorage.getItem('cart');
}

// Initialize cart count and display on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    displayCartItems();
});
