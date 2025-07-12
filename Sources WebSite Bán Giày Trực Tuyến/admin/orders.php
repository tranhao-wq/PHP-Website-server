<?php
require_once '../includes/config.php';
checkAdminLogin();
$page_title = "Quản lý đơn hàng";
include '../admin/admin-header.php';

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    $_SESSION['message'] = "Đã cập nhật trạng thái đơn hàng thành công";
    header("Location: orders.php");
    exit();
}

// Xử lý hủy đơn hàng
if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ? AND status = 'pending'");
    $stmt->execute([$_GET['id']]);
    $_SESSION['message'] = "Đã hủy đơn hàng thành công";
    header("Location: orders.php");
    exit();
}

// Xử lý lọc đơn hàng
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id";
$params = [];
if (!empty($status_filter)) {
    $sql .= " WHERE o.status = ?";
    $params[] = $status_filter;
}
$sql .= " ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xem chi tiết đơn hàng
if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT o.*, u.username, u.email, u.phone, u.address FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo '<div class="admin-container"><div class="alert alert-danger">Đơn hàng không tồn tại.</div></div>';
        include '../includes/footer.php';
        exit();
    }

    $stmt = $conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="admin-container">
        <h1>Chi tiết đơn hàng #<?php echo htmlspecialchars($order_id); ?></h1>

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
                <table class="admin-table">
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
                                    <img src="../assets/image/<?php echo htmlspecialchars($item['image'] ?? 'default-shoe.png'); ?>" width="50" class="me-2">
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

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <strong>Cập nhật trạng thái</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="orders.php">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                            <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Đang giao</option>
                            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary mt-2">Cập nhật</button>
                    <a href="orders.php" class="btn btn-secondary mt-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
    <?php
    include '../includes/footer.php';
    exit();
}
?>

<div class="admin-container">
    <h1>Quản lý đơn hàng</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="admin-actions">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                    <option value="processing" <?php echo $status_filter == 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="shipped" <?php echo $status_filter == 'shipped' ? 'selected' : ''; ?>>Đang giao</option>
                    <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                    <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ngày đặt</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo number_format($order['total'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                    <td>
                        <a href="orders.php?action=view&id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Xem</a>
                        <?php if ($order['status'] == 'pending'): ?>
                            <a href="orders.php?action=cancel&id=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">Hủy</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
