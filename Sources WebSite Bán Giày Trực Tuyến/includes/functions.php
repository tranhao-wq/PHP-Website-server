<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch($action) {
    case 'add_to_cart':
        addToCart();
        break;
    case 'update_cart_item':
        updateCartItem();
        break;
    case 'remove_cart_item':
        removeCartItem();
        break;
    case 'get_cart_count':
        getCartCount();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Action not found']);
}

function addToCart() {
    global $conn;
    
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        return;
    }
    
    $product_id = $_POST['product_id'] ?? 0;
    $user_id = $_SESSION['user_id'];
    $color = $_POST['color'] ?? null;
    $size = $_POST['size'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;
    
    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND COALESCE(color, '') = COALESCE(?, '') AND COALESCE(size, '') = COALESCE(?, '')");
    $stmt->execute([$user_id, $product_id, $color, $size]);
    $existing = $stmt->fetch();
    
    if($existing) {
        // Nếu có rồi thì tăng số lượng
        $new_quantity = $existing['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $existing['id']]);
    } else {
        // Nếu chưa có thì thêm mới
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, color, size) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity, $color, $size]);
    }
    
    // Thay vì trả về JSON, chuyển hướng về cart.php
    header("Location: ../cart.php");
    exit();
}

function updateCartItem() {
    global $conn;
    
    $cart_id = $_POST['cart_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    
    if($quantity < 1) $quantity = 1;
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cart_id]);
    
    echo json_encode(['success' => true]);
}

function removeCartItem() {
    global $conn;
    
    $cart_id = $_POST['cart_id'] ?? 0;
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);
    
    echo json_encode(['success' => true]);
}

function getCartCount() {
    global $conn;
    
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => true, 'count' => 0]);
        return;
    }
    
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    
    $count = $result['total'] ?? 0;
    
    echo json_encode(['success' => true, 'count' => $count]);
}
?>