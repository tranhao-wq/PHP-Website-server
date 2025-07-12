<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kết nối database
$host = 'localhost';
$dbname = 'thuadmin';
$username = 'root';
$password = 'root';
$port = 3307; // Cổng được thêm vào

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Thiết lập charset utf8
    $conn->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Hàm kiểm tra đăng nhập admin 
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: admin-login.php");
        exit();
    }
}

// Hàm kiểm tra đăng nhập user
function checkUserLogin() {
    if(!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>