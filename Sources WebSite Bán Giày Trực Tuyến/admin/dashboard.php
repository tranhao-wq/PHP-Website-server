<?php
require_once '../includes/config.php';
checkAdminLogin();
$page_title = "Bảng điều khiển quản trị";
include '../admin/admin-header.php';

// Lấy thống kê
$stmt = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

$stmt = $conn->query("SELECT SUM(total) as total_revenue FROM orders WHERE status = 'completed'");
$total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

$stmt = $conn->query("SELECT COUNT(*) as total_products FROM products WHERE is_active = 1");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$stmt = $conn->query("SELECT COUNT(*) as total_users FROM users WHERE is_active = 1");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Lấy 5 đơn hàng gần đây
$stmt = $conn->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10");
$stmt->execute();
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <h1>Bảng điều khiển quản trị</h1>

    <div class="stats-container">
        <div class="stat-card">
            <h3>Tổng đơn hàng</h3>
            <p><?php echo $total_orders; ?></p>
        </div>
        <div class="stat-card">
            <h3>Doanh thu (Hoàn thành)</h3>
            <p><?php echo number_format($total_revenue, 0, ',', '.'); ?> VNĐ</p>
        </div>
        <div class="stat-card">
            <h3>Sản phẩm</h3>
            <p><?php echo $total_products; ?></p>
        </div>
        <div class="stat-card">
            <h3>Người dùng</h3>
            <p><?php echo $total_users; ?></p>
        </div>
    </div>

    <h2>Đơn hàng gần đây</h2>
    <div class="table-responsive">
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
                <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                        <td><?php echo number_format($order['total'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                            <a href="orders.php?action=view&id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Xem</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
