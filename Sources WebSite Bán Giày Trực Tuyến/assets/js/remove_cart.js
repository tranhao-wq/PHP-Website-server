// Hàm xóa sản phẩm khỏi giỏ hàng
function removeCartItem(cartId) {
    if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        fetch('includes/remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_id=${cartId}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Cập nhật lại giao diện
                const itemRow = document.querySelector(`tr[data-cart-id="${cartId}"]`);
                if(itemRow) {
                    itemRow.remove();
                }
                
                // Cập nhật tổng tiền
                updateCartTotal();
                
                // Cập nhật số lượng giỏ hàng
                updateCartCount();
                
                alert('Đã xóa sản phẩm khỏi giỏ hàng');
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Không thể xóa sản phẩm'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi kết nối tới server');
        });
    }
}

// Hàm cập nhật tổng tiền
function updateCartTotal() {
    let total = 0;
    document.querySelectorAll('.cart-item-total').forEach(el => {
        total += parseFloat(el.textContent.replace(/[^\d.-]/g, ''));
    });
    document.querySelector('.cart-total').textContent = formatCurrency(total);
}

// Hàm định dạng tiền tệ
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

// Hàm cập nhật số lượng giỏ hàng
function updateCartCount() {
    fetch('includes/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const cartCountElement = document.getElementById('cart-count');
            if(cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        }
    });
}

// Gắn sự kiện cho các nút xóa
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.getAttribute('data-cart-id');
            removeCartItem(cartId);
        });
    });
});