<?php 
require_once 'includes/config.php';
$page_title = "Sản phẩm";
include 'includes/header.php'; 
?>
<div class="container my-5">
    <h2 class="text-center mb-4">Tất cả sản phẩm</h2>
    <div class="row mb-4">
        <div class="col-12 col-md-8 offset-md-2">
            <form method="GET" action="" class="row g-2 align-items-center justify-content-center">
                <div class="col-6 col-md-4">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        <option value="Thể thao"<?= (isset($_GET['category']) && $_GET['category']=='Thể thao')?' selected':''; ?>>Thể thao</option>
                        <option value="Chạy bộ"<?= (isset($_GET['category']) && $_GET['category']=='Chạy bộ')?' selected':''; ?>>Chạy bộ</option>
                        <option value="Công sở"<?= (isset($_GET['category']) && $_GET['category']=='Công sở')?' selected':''; ?>>Công sở</option>
                        <option value="Sneaker"<?= (isset($_GET['category']) && $_GET['category']=='Sneaker')?' selected':''; ?>>Sneaker</option>
                    </select>
                </div>
                <div class="col-6 col-md-4">
                    <select name="brand" class="form-select">
                        <option value="">Tất cả thương hiệu</option>
                        <option value="Nike"<?= (isset($_GET['brand']) && $_GET['brand']=='Nike')?' selected':''; ?>>Nike</option>
                        <option value="Adidas"<?= (isset($_GET['brand']) && $_GET['brand']=='Adidas')?' selected':''; ?>>Adidas</option>
                        <option value="Bitis"<?= (isset($_GET['brand']) && $_GET['brand']=='Bitis')?' selected':''; ?>>Bitis</option>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <?php
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];
        if(isset($_GET['category']) && !empty($_GET['category'])) {
            $sql .= " AND category = ?";
            $params[] = $_GET['category'];
        }
        if(isset($_GET['brand']) && !empty($_GET['brand'])) {
            $sql .= " AND brand = ?";
            $params[] = $_GET['brand'];
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $found = false;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $found = true;
            $img = !empty($row['image']) ? 'assets/image/' . $row['image'] : 'assets/image/default-shoe.png';
            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">';
            echo '  <div class="card h-100 shadow-sm">';
            echo '    <img src="' . $img . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '" style="height:200px;object-fit:cover;">';
            echo '    <div class="card-body d-flex flex-column">';
            echo '      <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
            echo '      <p class="card-text text-danger fw-bold">' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
            echo '      <a href="product-detail.php?id=' . $row['id'] . '" class="btn btn-outline-primary mb-2">Xem chi tiết</a>';
            echo '      <button class="btn btn-success add-to-cart mt-auto" data-id="' . $row['id'] . '">Thêm vào giỏ</button>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }
        if(!$found) {
            echo '<div class="col-12"><div class="alert alert-info text-center">Không tìm thấy sản phẩm phù hợp.</div></div>';
        }
        ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>