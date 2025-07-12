<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng giày - <?php echo $page_title ?? 'Trang chủ'; ?></title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="header-wrapper">
        <div class="header-container">
            <div class="logo">
                <a href="index.php">ShoeStore</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="products.php">Sản phẩm</a></li>
                    <li><a href="cart.php">Giỏ hàng</a></li>
                    <li><a href="my-orders.php">Đơn hàng</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php">Hồ sơ</a></li>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="logout.php">Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Đăng nhập</a></li>
                        <li><a href="register.php">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    <main class="main-content"></main>