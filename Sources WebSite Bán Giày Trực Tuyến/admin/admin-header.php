<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị - <?php echo $page_title ?? 'Bảng điều khiển'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header-wrapper">
        <div class="header-container">
            <div class="logo">
                <a href="dashboard.php">ShoeStore Admin</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="dashboard.php">Bảng điều khiển</a></li>
                    <li><a href="products.php">Sản phẩm</a></li>
                    <li><a href="orders.php">Đơn hàng</a></li>
                    <li><a href="users.php">Người dùng</a></li>
                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'superadmin'): ?>
                        <li><a href="admins.php">Quản trị viên</a></li>
                    <?php endif; ?>
                    <li><a href="admin-logout.php">Đăng xuất</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <main class="main-content">