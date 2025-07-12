<?php
require_once 'includes/config.php';
include 'includes/header.php';
?>
<div class="container-fluid p-0">
    <div class="bg-dark text-white text-center py-5 mb-4" style="background: url('assets/image/R.png') center/cover no-repeat; min-height: 250px;">
        <div class="bg-opacity-75 p-4">
            <h1 class="display-5 fw-bold">Chào mừng đến với cửa hàng giày</h1>
            <p class="lead">Khám phá bộ sưu tập giày mới nhất, đa dạng phong cách!</p>
            <a href="products.php" class="btn btn-warning btn-lg mt-3">Xem sản phẩm</a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <h2 class="text-center mb-4">Sản phẩm nổi bật</h2>
    <div class="row g-4 justify-content-center">
        <?php
        $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 8");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $img = !empty($row['image']) ? 'assets/image/' . $row['image'] : 'assets/image/default-shoe.png';
            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">';
            echo '  <div class="card h-100 shadow">';
            echo '    <img src="' . $img . '" class="card-img-top mx-auto d-block" alt="' . htmlspecialchars($row['name']) . '" style="height:200px;object-fit:contain;">';
            echo '    <div class="card-body d-flex flex-column">';
            echo '      <h5 class="card-title text-center">' . htmlspecialchars($row['name']) . '</h5>';
            echo '      <p class="card-text text-danger fw-bold text-center">' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
            echo '      <a href="product-detail.php?id=' . $row['id'] . '" class="btn btn-outline-primary mt-auto">Xem chi tiết</a>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }
        ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>