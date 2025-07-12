<?php
require_once '../includes/config.php';
checkAdminLogin();
$page_title = "Quản lý sản phẩm";
include '../admin/admin-header.php';

// Xử lý xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $_SESSION['message'] = "Đã xóa sản phẩm thành công";
    header("Location: products.php");
    exit();
}

// Xử lý thêm/sửa sản phẩm
if (isset($_POST['add_product']) || isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $color = $_POST['color'];
    $material = $_POST['material'];
    $gender = $_POST['gender'];
    $season = $_POST['season'];
    $style = $_POST['style'];
    $stock_quantity = $_POST['stock_quantity'];

    // Xử lý upload ảnh
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/image/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $_FILES["image"]["name"];
    }

    try {
        if (isset($_POST['update_product']) && isset($_POST['id'])) {
            // Cập nhật sản phẩm
            $id = $_POST['id'];
            if ($image) {
                $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ?, brand = ?, color = ?, material = ?, gender = ?, season = ?, style = ?, stock_quantity = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$name, $description, $price, $image, $category, $brand, $color, $material, $gender, $season, $style, $stock_quantity, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, brand = ?, color = ?, material = ?, gender = ?, season = ?, style = ?, stock_quantity = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$name, $description, $price, $category, $brand, $color, $material, $gender, $season, $style, $stock_quantity, $id]);
            }
            $_SESSION['message'] = "Đã cập nhật sản phẩm thành công";
        } else {
            // Thêm sản phẩm mới
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category, brand, color, material, gender, season, style, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image, $category, $brand, $color, $material, $gender, $season, $style, $stock_quantity]);
            $_SESSION['message'] = "Đã thêm sản phẩm thành công";
        }
        header("Location: products.php");
        exit();
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}

// Xử lý thêm/sửa biến thể
if (isset($_POST['add_variant']) || isset($_POST['update_variant'])) {
    $product_id = $_POST['product_id'];
    $color = $_POST['variant_color'];
    $size = $_POST['variant_size'];
    $stock_quantity = $_POST['variant_stock_quantity'];

    try {
        if (isset($_POST['update_variant']) && isset($_POST['variant_id'])) {
            $stmt = $conn->prepare("UPDATE product_variants SET color = ?, size = ?, stock_quantity = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$color, $size, $stock_quantity, $_POST['variant_id']]);
            $_SESSION['message'] = "Đã cập nhật biến thể thành công";
        } else {
            $stmt = $conn->prepare("INSERT INTO product_variants (product_id, color, size, stock_quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $color, $size, $stock_quantity]);
            $_SESSION['message'] = "Đã thêm biến thể thành công";
        }
        header("Location: products.php");
        exit();
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}

// Xử lý xóa biến thể
if (isset($_GET['action']) && $_GET['action'] == 'delete_variant' && isset($_GET['variant_id'])) {
    $stmt = $conn->prepare("DELETE FROM product_variants WHERE id = ?");
    $stmt->execute([$_GET['variant_id']]);
    $_SESSION['message'] = "Đã xóa biến thể thành công";
    header("Location: products.php");
    exit();
}
?>

<div class="admin-container">
    <h1>Quản lý sản phẩm</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="admin-actions">
        <button id="add-product-btn" class="btn">Thêm sản phẩm</button>
    </div>

    <div id="add-product-form" style="display: none;">
        <h2>Thêm sản phẩm mới</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá</label>
                <input type="number" name="price" id="price" min="0" required>
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh</label>
                <input type="file" name="image" id="image">
            </div>
            <div class="form-group">
                <label for="category">Danh mục</label>
                <select name="category" id="category" required>
                    <option value="Thể thao">Thể thao</option>
                    <option value="Chạy bộ">Chạy bộ</option>
                    <option value="Công sở">Công sở</option>
                    <option value="Sneaker">Sneaker</option>
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Thương hiệu</label>
                <select name="brand" id="brand" required>
                    <option value="Nike">Nike</option>
                    <option value="Adidas">Adidas</option>
                    <option value="Bitis">Bitis</option>
                    <option value="Puma">Puma</option>
                    <option value="Asics">Asics</option>
                    <option value="Pierre Cardin">Pierre Cardin</option>
                    <option value="Converse">Converse</option>
                    <option value="Vans">Vans</option>
                    <option value="New Balance">New Balance</option>
                </select>
            </div>
            <div class="form-group">
                <label for="color">Màu sắc</label>
                <input type="text" name="color" id="color">
            </div>
            <div class="form-group">
                <label for="material">Chất liệu</label>
                <input type="text" name="material" id="material">
            </div>
            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select name="gender" id="gender">
                    <option value="Unisex">Unisex</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="season">Mùa</label>
                <input type="text" name="season" id="season">
            </div>
            <div class="form-group">
                <label for="style">Phong cách</label>
                <input type="text" name="style" id="style">
            </div>
            <div class="form-group">
                <label for="stock_quantity">Số lượng tồn kho</label>
                <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="0">
            </div>
            <button type="submit" name="add_product" class="btn">Thêm</button>
            <button type="button" id="cancel-add" class="btn">Hủy</button>
        </form>
    </div>

    <div class="admin-actions">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    <option value="Thể thao" <?php echo isset($_GET['category']) && $_GET['category'] == 'Thể thao' ? 'selected' : ''; ?>>Thể thao</option>
                    <option value="Chạy bộ" <?php echo isset($_GET['category']) && $_GET['category'] == 'Chạy bộ' ? 'selected' : ''; ?>>Chạy bộ</option>
                    <option value="Công sở" <?php echo isset($_GET['category']) && $_GET['category'] == 'Công sở' ? 'selected' : ''; ?>>Công sở</option>
                    <option value="Sneaker" <?php echo isset($_GET['category']) && $_GET['category'] == 'Sneaker' ? 'selected' : ''; ?>>Sneaker</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="brand" class="form-select">
                    <option value="">Tất cả thương hiệu</option>
                    <option value="Nike" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Nike' ? 'selected' : ''; ?>>Nike</option>
                    <option value="Adidas" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Adidas' ? 'selected' : ''; ?>>Adidas</option>
                    <option value="Bitis" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Bitis' ? 'selected' : ''; ?>>Bitis</option>
                    <option value="Puma" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Puma' ? 'selected' : ''; ?>>Puma</option>
                    <option value="Asics" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Asics' ? 'selected' : ''; ?>>Asics</option>
                    <option value="Pierre Cardin" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Pierre Cardin' ? 'selected' : ''; ?>>Pierre Cardin</option>
                    <option value="Converse" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Converse' ? 'selected' : ''; ?>>Converse</option>
                    <option value="Vans" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'Vans' ? 'selected' : ''; ?>>Vans</option>
                    <option value="New Balance" <?php echo isset($_GET['brand']) && $_GET['brand'] == 'New Balance' ? 'selected' : ''; ?>>New Balance</option>
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
                <th>Tên</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Tồn kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM products WHERE 1=1";
            $params = [];
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $sql .= " AND name LIKE ?";
                $params[] = '%' . $_GET['search'] . '%';
            }
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $sql .= " AND category = ?";
                $params[] = $_GET['category'];
            }
            if (isset($_GET['brand']) && !empty($_GET['brand'])) {
                $sql .= " AND brand = ?";
                $params[] = $_GET['brand'];
            }
            $sql .= " ORDER BY id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($product['id']) . '</td>';
                echo '<td>' . htmlspecialchars($product['name']) . '</td>';
                echo '<td>' . number_format($product['price'], 0, ',', '.') . ' VNĐ</td>';
                echo '<td>' . htmlspecialchars($product['category']) . '</td>';
                echo '<td>' . htmlspecialchars($product['brand']) . '</td>';
                echo '<td>' . htmlspecialchars($product['stock_quantity']) . '</td>';
                echo '<td>';
                echo '<a href="products.php?action=edit&id=' . $product['id'] . '" class="btn">Sửa</a> ';
                echo '<a href="products.php?action=delete&id=' . $product['id'] . '" class="btn btn-danger" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')">Xóa</a>';
                echo '</td>';
                echo '</tr>';

                // Form sửa sản phẩm
                if (isset($_GET['action']) && $_GET['action'] == 'edit' && $_GET['id'] == $product['id']) {
                    echo '<tr id="edit-form-' . $product['id'] . '">';
                    echo '<td colspan="7">';
                    echo '<form method="POST" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="id" value="' . $product['id'] . '">';
                    echo '<div class="form-group"><label>Tên sản phẩm</label><input type="text" name="name" value="' . htmlspecialchars($product['name']) . '" required></div>';
                    echo '<div class="form-group"><label>Mô tả</label><textarea name="description" required>' . htmlspecialchars($product['description']) . '</textarea></div>';
                    echo '<div class="form-group"><label>Giá</label><input type="number" name="price" value="' . $product['price'] . '" min="0" required></div>';
                    echo '<div class="form-group"><label>Hình ảnh</label><input type="file" name="image" id="image"></div>';
                    echo '<div class="form-group"><label>Danh mục</label><select name="category" required>';
                    echo '<option value="Thể thao" ' . ($product['category'] == 'Thể thao' ? 'selected' : '') . '>Thể thao</option>';
                    echo '<option value="Chạy bộ" ' . ($product['category'] == 'Chạy bộ' ? 'selected' : '') . '>Chạy bộ</option>';
                    echo '<option value="Công sở" ' . ($product['category'] == 'Công sở' ? 'selected' : '') . '>Công sở</option>';
                    echo '<option value="Sneaker" ' . ($product['category'] == 'Sneaker' ? 'selected' : '') . '>Sneaker</option>';
                    echo '</select></div>';
                    echo '<div class="form-group"><label>Thương hiệu</label><select name="brand" required>';
                    echo '<option value="Nike" ' . ($product['brand'] == 'Nike' ? 'selected' : '') . '>Nike</option>';
                    echo '<option value="Adidas" ' . ($product['brand'] == 'Adidas' ? 'selected' : '') . '>Adidas</option>';
                    echo '<option value="Bitis" ' . ($product['brand'] == 'Bitis' ? 'selected' : '') . '>Bitis</option>';
                    echo '<option value="Puma" ' . ($product['brand'] == 'Puma' ? 'selected' : '') . '>Puma</option>';
                    echo '<option value="Asics" ' . ($product['brand'] == 'Asics' ? 'selected' : '') . '>Asics</option>';
                    echo '<option value="Pierre Cardin" ' . ($product['brand'] == 'Pierre Cardin' ? 'selected' : '') . '>Pierre Cardin</option>';
                    echo '<option value="Converse" ' . ($product['brand'] == 'Converse' ? 'selected' : '') . '>Converse</option>';
                    echo '<option value="Vans" ' . ($product['brand'] == 'Vans' ? 'selected' : '') . '>Vans</option>';
                    echo '<option value="New Balance" ' . ($product['brand'] == 'New Balance' ? 'selected' : '') . '>New Balance</option>';
                    echo '</select></div>';
                    echo '<div class="form-group"><label>Màu sắc</label><input type="text" name="color" value="' . htmlspecialchars($product['color'] ?? '') . '"></div>';
                    echo '<div class="form-group"><label>Chất liệu</label><input type="text" name="material" value="' . htmlspecialchars($product['material'] ?? '') . '"></div>';
                    echo '<div class="form-group"><label>Giới tính</label><select name="gender">';
                    echo '<option value="Unisex" ' . ($product['gender'] == 'Unisex' ? 'selected' : '') . '>Unisex</option>';
                    echo '<option value="Nam" ' . ($product['gender'] == 'Nam' ? 'selected' : '') . '>Nam</option>';
                    echo '<option value="Nữ" ' . ($product['gender'] == 'Nữ' ? 'selected' : '') . '>Nữ</option>';
                    echo '</select></div>';
                    echo '<div class="form-group"><label>Mùa</label><input type="text" name="season" value="' . htmlspecialchars($product['season'] ?? '') . '"></div>';
                    echo '<div class="form-group"><label>Phong cách</label><input type="text" name="style" value="' . htmlspecialchars($product['style'] ?? '') . '"></div>';
                    echo '<div class="form-group"><label>Số lượng tồn kho</label><input type="number" name="stock_quantity" value="' . $product['stock_quantity'] . '" min="0"></div>';
                    echo '<button type="submit" name="update_product" class="btn">Cập nhật</button>';
                    echo '<a href="products.php" class="btn">Hủy</a>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';

                    // Hiển thị biến thể sản phẩm
                    $stmt_variant = $conn->prepare("SELECT * FROM product_variants WHERE product_id = ?");
                    $stmt_variant->execute([$product['id']]);
                    $variants = $stmt_variant->fetchAll(PDO::FETCH_ASSOC);
                    if ($variants) {
                        echo '<tr><td colspan="7"><strong>Biến thể:</strong><br>';
                        echo '<table class="table table-bordered mt-2">';
                        echo '<thead><tr><th>Màu sắc</th><th>Kích cỡ</th><th>Tồn kho</th><th>Hành động</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($variants as $variant) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($variant['color']) . '</td>';
                            echo '<td>' . htmlspecialchars($variant['size']) . '</td>';
                            echo '<td>' . htmlspecialchars($variant['stock_quantity']) . '</td>';
                            echo '<td>';
                            echo '<a href="products.php?action=edit_variant&variant_id=' . $variant['id'] . '" class="btn btn-sm">Sửa</a> ';
                            echo '<a href="products.php?action=delete_variant&variant_id=' . $variant['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')">Xóa</a>';
                            echo '</td>';
                            echo '</tr>';

                            if (isset($_GET['action']) && $_GET['action'] == 'edit_variant' && $_GET['variant_id'] == $variant['id']) {
                                echo '<tr>';
                                echo '<td colspan="4">';
                                echo '<form method="POST" action="products.php">';
                                echo '<input type="hidden" name="variant_id" value="' . $variant['id'] . '">';
                                echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
                                echo '<div class="form-group"><label>Màu sắc</label><input type="text" name="variant_color" value="' . htmlspecialchars($variant['color']) . '" required></div>';
                                echo '<div class="form-group"><label>Kích cỡ</label><input type="text" name="variant_size" value="' . htmlspecialchars($variant['size']) . '" required></div>';
                                echo '<div class="form-group"><label>Số lượng tồn kho</label><input type="number" name="variant_stock_quantity" value="' . $variant['stock_quantity'] . '" min="0" required></div>';
                                echo '<button type="submit" name="update_variant" class="btn">Cập nhật</button>';
                                echo '<a href="products.php" class="btn">Hủy</a>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                        echo '</tbody></table>';
                        echo '</td></tr>';
                    }

                    // Form thêm biến thể
                    echo '<tr>';
                    echo '<td colspan="7">';
                    echo '<form method="POST" action="products.php">';
                    echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
                    echo '<strong>Thêm biến thể mới:</strong><br>';
                    echo '<div class="form-group"><label>Màu sắc</label><input type="text" name="variant_color" required></div>';
                    echo '<div class="form-group"><label>Kích cỡ</label><input type="text" name="variant_size" required></div>';
                    echo '<div class="form-group"><label>Số lượng tồn kho</label><input type="number" name="variant_stock_quantity" min="0" required></div>';
                    echo '<button type="submit" name="add_variant" class="btn">Thêm biến thể</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('add-product-btn').addEventListener('click', function() {
    document.getElementById('add-product-form').style.display = 'block';
});
document.getElementById('cancel-add').addEventListener('click', function() {
    document.getElementById('add-product-form').style.display = 'none';
});
</script>
