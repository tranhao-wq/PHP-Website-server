document.addEventListener('DOMContentLoaded', function() {
    // Thêm vào giỏ hàng
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            addToCart(productId);
        });
    });
    
    // Cập nhật số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.getAttribute('data-id');
            const quantity = this.value;
            updateCartItem(cartId, quantity);
        });
    });
    
    // Xóa khỏi giỏ hàng
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.getAttribute('data-id');
            removeCartItem(cartId);
        });
    });
});

function addToCart(productId) {
    fetch('includes/functions.php?action=add_to_cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Sản phẩm đã được thêm vào giỏ hàng');
            updateCartCount();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartItem(cartId, quantity) {
    fetch('includes/functions.php?action=update_cart_item', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeCartItem(cartId) {
    if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        fetch('includes/functions.php?action=remove_cart_item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_id=${cartId}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function updateCartCount() {
    fetch('includes/functions.php?action=get_cart_count')
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const cartCountElement = document.getElementById('cart-count');
            if(cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        }
    })
    .catch(error => console.error('Error:', error));
}