<?php
require_once '../includes/config.php';
checkAdminLogin();
$page_title = "Quản lý người dùng";
include '../admin/admin-header.php';

// Xử lý xóa người dùng
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $_SESSION['message'] = "Đã xóa người dùng thành công";
    header("Location: users.php");
    exit();
}

// Xử lý vô hiệu hóa/kích hoạt người dùng
if (isset($_GET['action']) && $_GET['action'] == 'toggle_active' && isset($_GET['id'])) {
    $stmt = $conn->prepare("UPDATE users SET is_active = IF(is_active = 1, 0, 1), updated_at = NOW() WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $_SESSION['message'] = "Đã cập nhật trạng thái người dùng";
    header("Location: users.php");
    exit();
}

// Xử lý thêm/sửa người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Cập nhật người dùng
            $id = $_POST['id'];
            if ($password) {
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, email = ?, full_name = ?, address = ?, phone = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$username, $password, $email, $full_name, $address, $phone, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, address = ?, phone = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$username, $email, $full_name, $address, $phone, $id]);
            }
            $_SESSION['message'] = "Đã cập nhật người dùng thành công";
        } else {
            // Thêm người dùng mới
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, full_name, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $password, $email, $full_name, $address, $phone]);
            $_SESSION['message'] = "Đã thêm người dùng thành công";
        }
        header("Location: users.php");
        exit();
    } catch (PDOException $e) {
        $error = $e->getCode() == 23000 ? "Tên đăng nhập hoặc email đã tồn tại." : "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="admin-container">
    <h1>Quản lý người dùng</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="admin-actions">
        <button id="add-user-btn" class="btn">Thêm người dùng</button>
    </div>

    <div id="add-user-form" style="display: none;">
        <h2>Thêm người dùng mới</h2>
        <form method="POST" action="users.php">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" name="address" id="address">
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" name="phone" id="phone">
            </div>
            <button type="submit" class="btn">Thêm</button>
            <button type="button" id="cancel-add" class="btn">Hủy</button>
        </form>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ và tên</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($user['id']) . '</td>';
                echo '<td>' . htmlspecialchars($user['username']) . '</td>';
                echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                echo '<td>' . htmlspecialchars($user['full_name']) . '</td>';
                echo '<td>' . ($user['is_active'] ? 'Kích hoạt' : 'Vô hiệu hóa') . '</td>';
                echo '<td>';
                echo '<a href="users.php?action=edit&id=' . $user['id'] . '" class="btn">Sửa</a> ';
                echo '<a href="users.php?action=delete&id=' . $user['id'] . '" class="btn btn-danger" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')">Xóa</a> ';
                echo '<a href="users.php?action=toggle_active&id=' . $user['id'] . '" class="btn">' . ($user['is_active'] ? 'Vô hiệu hóa' : 'Kích hoạt') . '</a>';
                echo '</td>';
                echo '</tr>';

                if (isset($_GET['action']) && $_GET['action'] == 'edit' && $_GET['id'] == $user['id']) {
                    echo '<tr id="edit-form-' . $user['id'] . '">';
                    echo '<td colspan="6">';
                    echo '<form method="POST" action="users.php">';
                    echo '<input type="hidden" name="id" value="' . $user['id'] . '">';
                    echo '<div class="form-group"><label>Tên đăng nhập</label><input type="text" name="username" value="' . htmlspecialchars($user['username']) . '" required></div>';
                    echo '<div class="form-group"><label>Mật khẩu (để trống nếu không đổi)</label><input type="password" name="password" id="password"></div>';
                    echo '<div class="form-group"><label>Email</label><input type="email" name="email" value="' . htmlspecialchars($user['email']) . '" required></div>';
                    echo '<div class="form-group"><label>Họ và tên</label><input type="text" name="full_name" value="' . htmlspecialchars($user['full_name']) . '" required></div>';
                    echo '<div class="form-group"><label>Địa chỉ</label><input type="text" name="address" value="' . htmlspecialchars($user['address'] ?? '') . '"></div>';
                    echo '<div class="form-group"><label>Số điện thoại</label><input type="text" name="phone" value="' . htmlspecialchars($user['phone'] ?? '') . '"></div>';
                    echo '<button type="submit" class="btn">Cập nhật</button>';
                    echo '<a href="users.php" class="btn">Hủy</a>';
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
document.getElementById('add-user-btn').addEventListener('click', function() {
    document.getElementById('add-user-form').style.display = 'block';
});
document.getElementById('cancel-add').addEventListener('click', function() {
    document.getElementById('add-user-form').style.display = 'none';
});
</script>
