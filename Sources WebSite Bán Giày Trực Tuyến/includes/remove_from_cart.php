<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện thao tác này']);
    exit();
}

if(!isset($_POST['cart_id']) || empty($_POST['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin giỏ hàng']);
    exit();
}

$cart_id = $_POST['cart_id'];
$user_id = $_SESSION['user_id'];

try {
    // Kiểm tra xem sản phẩm có thuộc về người dùng hiện tại không
    $stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);
    
    if($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng của bạn']);
        exit();
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
}
?>