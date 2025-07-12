<?php
require_once 'includes/config.php';
$page_title = "Đơn hàng của tôi";
include 'includes/header.php';

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Xem chi tiết đơn hàng
if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    
    // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
    $stmt = $conn->prepare("
        SELECT o.*, u.username, u.email, u.phone, u.address 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo '<div class="container my-5"><div class="alert alert-danger">Đơn hàng không tồn tại hoặc bạn không có quyền xem.</div></div>';
        include 'includes/footer.php';
        exit();
    }
    
    // Lấy chi tiết đơn hàng
    $stmt = $conn->prepare("
        SELECT oi.*, p.name, p.image 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <div class="container my-5">
        <h2 class="mb-4">Chi tiết đơn hàng #<?php echo htmlspecialchars($order_id); ?></h2>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Thông tin đơn hàng</strong>
            </div>
            <div class="card-body">
                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total'], 0, ',', '.'); ?> VNĐ</p>
                <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <strong>Thông tin khách hàng</strong>
            </div>
            <div class="card-body">
                <p><strong>Tên:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'Chưa cung cấp'); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address'] ?? 'Chưa cung cấp'); ?></p>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <strong>Chi tiết sản phẩm</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td>
                                    <img src="assets/image/<?php echo htmlspecialchars($item['image'] ?? 'default-shoe.png'); ?>" width="50" class="me-2">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </td>
                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <a href="my-orders.php" class="btn btn-outline-primary mt-3">Quay lại danh sách đơn hàng</a>
    </div>
    
    <?php
    include 'includes/footer.php';
    exit();
}
?>

<div class="container my-5">
    <h2 class="mb-4 text-center">Đơn hàng của bạn</h2>
    
    <?php
    // Lấy danh sách đơn hàng của người dùng
    $stmt = $conn->prepare("
        SELECT o.*, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orders) > 0) {
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Phương thức thanh toán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td><?php echo number_format($order['total'], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                            <td>
                                <a href="my-orders.php?action=view&id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-info text-center">Bạn chưa có đơn hàng nào.</div>';
        echo '<div class="text-center"><a href="products.php" class="btn btn-outline-primary">Tiếp tục mua sắm</a></div>';
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>