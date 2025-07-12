<?php
require_once 'includes/config.php';
$page_title = "Đăng nhập";
include 'includes/header.php';

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    if(empty($login) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        // Cho phép đăng nhập bằng username hoặc email
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Tên đăng nhập/email hoặc mật khẩu không đúng.";
        }
    }
}
?>

<div class="container mt-5" style="max-width:450px;">
    <h2 class="mb-4 text-center">Đăng nhập</h2>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="login" class="form-label">Tên đăng nhập hoặc Email</label>
            <input type="text" class="form-control" name="login" id="login" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
    </form>
    <p class="mt-3 text-center">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>
<?php include 'includes/footer.php'; ?>