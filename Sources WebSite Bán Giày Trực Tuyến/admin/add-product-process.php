<?php
require_once 'includes/config.php';

$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'];

// Xử lý ảnh
$imageName = $_FILES['image']['name'];
$imageTmp = $_FILES['image']['tmp_name'];
$targetDir = 'assets/image/';
move_uploaded_file($imageTmp, $targetDir . $imageName);

// Lưu vào CSDL
$stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $price, $description, $imageName]);

header("Location: index.php");
exit();
?>
