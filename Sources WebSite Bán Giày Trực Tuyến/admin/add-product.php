<form action="add-product-process.php" method="POST" enctype="multipart/form-data">
    <label>Tên sản phẩm:</label>
    <input type="text" name="name" required><br>

    <label>Giá (VNĐ):</label>
    <input type="number" name="price" required><br>

    <label>Mô tả chi tiết:</label>
    <textarea name="description" required></textarea><br>

    <label>Ảnh sản phẩm:</label>
    <input type="file" name="image" accept="image/*" required><br>

    <button type="submit">Thêm sản phẩm</button>
</form>
