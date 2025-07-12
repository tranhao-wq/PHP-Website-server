<?php
require_once 'includes/config.php';
$page_title = "Cá nhân";

include 'includes/header.php';

// Lấy thông tin người dùng
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, full_name, address, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Không tìm thấy thông tin người dùng.";
    header("Location: index.php");
    exit();
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        if ($password) {
            $stmt = $conn->prepare("UPDATE users SET email = ?, full_name = ?, address = ?, phone = ?, password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$email, $full_name, $address, $phone, $password, $user_id]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET email = ?, full_name = ?, address = ?, phone = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$email, $full_name, $address, $phone, $user_id]);
        }
        $_SESSION['message'] = "Cập nhật thông tin thành công!";
        header("Location: profile.php");
        exit();
    } catch (PDOException $e) {
        $error = $e->getCode() == 23000 ? "Email đã tồn tại." : "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Hồ sơ cá nhân</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body">
            <form method="POST" action="profile.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
<br>
<?php include 'includes/footer.php'; ?>