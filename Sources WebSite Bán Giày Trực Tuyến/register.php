<?php
require_once 'includes/config.php';
$page_title = "Đăng ký";
include 'includes/header.php';

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    // Validate
    if(empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($full_name)) {
        $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } elseif($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } else {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $email, $full_name, $address, $phone]);
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } catch(PDOException $e) {
            if($e->getCode() == 23000) {
                $error = "Tên đăng nhập hoặc email đã tồn tại.";
            } else {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="container mt-5" style="max-width:500px;">
    <h2 class="mb-4 text-center">Đăng ký tài khoản</h2>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập *</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu *</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu *</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Họ và tên *</label>
            <input type="text" class="form-control" name="full_name" id="full_name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" name="address" id="address">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" name="phone" id="phone">
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
    </form>
    <p class="mt-3 text-center">Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
</div>
<?php include 'includes/footer.php'; ?>