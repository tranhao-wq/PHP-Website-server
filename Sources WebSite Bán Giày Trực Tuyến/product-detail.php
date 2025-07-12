<?php 
require_once 'includes/config.php';

if(!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$page_title = "Chi tiết sản phẩm";
include 'includes/header.php';

$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product) {
    echo "<p>Sản phẩm không tồn tại</p>";
    include 'includes/footer.php';
    exit();
}

// Lấy biến thể sản phẩm
$stmtVar = $conn->prepare("SELECT * FROM product_variants WHERE product_id = ?");
$stmtVar->execute([$product_id]);
$variants = $stmtVar->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách màu và size duy nhất
$colors = array_unique(array_filter(array_column($variants, 'color')));
$sizes = array_unique(array_filter(array_column($variants, 'size')));

$img = !empty($product['image']) ? 'assets/image/' . $product['image'] : 'assets/image/default-shoe.png';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="border rounded shadow-sm p-3 bg-white">
                <img src="<?= $img ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height:350px;object-fit:contain;">
            </div>
        </div>
        <div class="col-md-7">
            <h2 class="mb-2"><?= htmlspecialchars($product['name']) ?></h2>
            <p class="text-danger fw-bold fs-4 mb-2"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</p>
            <p class="mb-1">Danh mục: <span class="fw-semibold"><?= htmlspecialchars($product['category']) ?></span></p>
            <p class="mb-1">Thương hiệu: <span class="fw-semibold"><?= htmlspecialchars($product['brand']) ?></span></p>
            <div class="mb-3">
                <h5 class="mb-1">Mô tả sản phẩm</h5>
                <div><?= nl2br(htmlspecialchars($product['description'])) ?></div>
            </div>
            <?php if($variants && (count($colors) || count($sizes))): ?>
            <form method="POST" action="includes/functions.php?action=add_to_cart" class="mb-3">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <?php if(count($colors)): ?>
                <div class="mb-2">
                    <label for="color" class="form-label">Màu sắc:</label>
                    <select name="color" id="color" class="form-select" required>
                        <option value="">Chọn màu</option>
                        <?php foreach($colors as $color): ?>
                            <option value="<?= htmlspecialchars($color) ?>"><?= htmlspecialchars($color) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <?php if(count($sizes)): ?>
                <div class="mb-2">
                    <label for="size" class="form-label">Kích cỡ:</label>
                    <select name="size" id="size" class="form-select" required>
                        <option value="">Chọn size</option>
                        <?php foreach($sizes as $size): ?>
                            <option value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" style="width:120px;">
                </div>
                <button type="submit" class="btn btn-success">Thêm vào giỏ hàng</button>
                <a href="cart.php" class="btn btn-outline-primary ms-2">Xem giỏ hàng</a>
            </form>
            <?php else: ?>
                <div class="mb-3">
                    <form method="POST" action="includes/functions.php?action=add_to_cart">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-success">Thêm vào giỏ hàng</button>
                        <a href="cart.php" class="btn btn-outline-primary ms-2">Xem giỏ hàng</a>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>