<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Giỏ hàng";
include 'includes/header.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Giỏ hàng của bạn</h2>
    <?php
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price, p.image, pv.color, pv.size 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        LEFT JOIN product_variants pv ON c.product_id = pv.product_id 
            AND pv.color = c.color 
            AND pv.size = c.size 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($cart_items) > 0) {
        $total = 0;
        echo '<div class="table-responsive"><table class="table align-middle table-bordered cart-table">';
        echo '<thead class="table-light"><tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Tổng</th><th>Thuộc tính</th><th>Hành động</th></tr></thead><tbody>';
        foreach($cart_items as $item) {
            $item_total = $item['price'] * $item['quantity'];
            $total += $item_total;
            $img = !empty($item['image']) ? 'assets/image/' . $item['image'] : 'assets/image/default-shoe.png';
            echo '<tr>';
            echo '<td><img src="' . $img . '" width="50" class="me-2">' . htmlspecialchars($item['name']) . '</td>';
            echo '<td>' . number_format($item['price'], 0, ',', '.') . ' VNĐ</td>';
            echo '<td><input type="number" value="' . $item['quantity'] . '" min="1" class="form-control quantity-input" style="width:80px;" data-id="' . $item['id'] . '"></td>';
            echo '<td>' . number_format($item_total, 0, ',', '.') . ' VNĐ</td>';
            // Hiển thị thuộc tính size/màu nếu có
            $attrs = [];
            if (!empty($item['color'])) $attrs[] = 'Màu: ' . htmlspecialchars($item['color']);
            if (!empty($item['size'])) $attrs[] = 'Size: ' . htmlspecialchars($item['size']);
            echo '<td>' . implode('<br>', $attrs) . '</td>';
            echo '<td><button class="btn btn-danger remove-from-cart" data-id="' . $item['id'] . '">Xóa</button></td>';
            echo '</tr>';
        }
        echo '<tr><td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td><td class="fw-bold text-danger">' . number_format($total, 0, ',', '.') . ' VNĐ</td><td colspan="2"></td></tr>';
        echo '</tbody></table></div>';
        echo '<div class="d-flex justify-content-between mt-4">';
        echo '<a href="products.php" class="btn btn-outline-primary">&larr; Tiếp tục mua sắm</a>';
        echo '<a href="checkout.php" class="btn btn-success">Thanh toán</a>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center">Giỏ hàng của bạn đang trống</div>';
        echo '<div class="text-center"><a href="products.php" class="btn btn-outline-primary">Tiếp tục mua sắm</a></div>';
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>

