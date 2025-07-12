<?php
require_once '../includes/config.php';
checkAdminLogin();
$page_title = "Quản lý quản trị viên";
include '../admin/admin-header.php';

// Chỉ superadmin được truy cập
if ($_SESSION['admin_role'] != 'superadmin') {
    header("Location: dashboard.php");
    exit();
}

// Xử lý xóa admin
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM admins WHERE id = ? AND id != ?");
    $stmt->execute([$_GET['id'], $_SESSION['admin_id']]); // Không cho phép tự xóa
    $_SESSION['message'] = "Đã xóa quản trị viên thành công";
    header("Location: admins.php");
    exit();
}

// Xử lý thêm/sửa admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Cập nhật admin
            $id = $_POST['id'];
            if ($password) {
                $stmt = $conn->prepare("UPDATE admins SET username = ?, password_hash = ?, full_name = ?, role = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$username, $password, $full_name, $role, $id]);
            } else {
                $stmt = $conn->prepare("UPDATE admins SET username = ?, full_name = ?, role = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$username, $full_name, $role, $id]);
            }
            $_SESSION['message'] = "Đã cập nhật quản trị viên thành công";
        } else {
            // Thêm admin mới
            $stmt = $conn->prepare("INSERT INTO admins (username, password_hash, full_name, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $password, $full_name, $role]);
            $_SESSION['message'] = "Đã thêm quản trị viên thành công";
        }
        header("Location: admins.php");
        exit();
    } catch (PDOException $e) {
        $error = $e->getCode() == 23000 ? "Tên đăng nhập đã tồn tại." : "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="admin-container">
    <h1>Quản lý quản trị viên</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="admin-actions">
        <button id="add-admin-btn" class="btn">Thêm quản trị viên</button>
    </div>

    <div id="add-admin-form" style="display: none;">
        <h2>Thêm quản trị viên mới</h2>
        <form method="POST" action="admins.php">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò</label>
                <select name="role" id="role" required>
                    <option value="manager">Quản lý</option>
                    <option value="superadmin">Superadmin</option>
                </select>
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
                <th>Họ và tên</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM admins ORDER BY id DESC");
            while ($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($admin['id']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['username']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($admin['role']) . '</td>';
                echo '<td>';
                echo '<a href="admins.php?action=edit&id=' . $admin['id'] . '" class="btn">Sửa</a> ';
                if ($admin['id'] != $_SESSION['admin_id']) {
                    echo '<a href="admins.php?action=delete&id=' . $admin['id'] . '" class="btn btn-danger" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')">Xóa</a>';
                }
                echo '</td>';
                echo '</tr>';

                if (isset($_GET['action']) && $_GET['action'] == 'edit' && $_GET['id'] == $admin['id']) {
                    echo '<tr id="edit-form-' . $admin['id'] . '">';
                    echo '<td colspan="5">';
                    echo '<form method="POST" action="admins.php">';
                    echo '<input type="hidden" name="id" value="' . $admin['id'] . '">';
                    echo '<div class="form-group"><label>Tên đăng nhập</label><input type="text" name="username" value="' . htmlspecialchars($admin['username']) . '" required></div>';
                    echo '<div class="form-group"><label>Mật khẩu (để trống nếu không đổi)</label><input type="password" name="password" id="password"></div>';
                    echo '<div class="form-group"><label>Họ và tên</label><input type="text" name="full_name" value="' . htmlspecialchars($admin['full_name']) . '" required></div>';
                    echo '<div class="form-group"><label>Vai trò</label><select name="role" required>';
                    echo '<option value="manager"' . ($admin['role'] == 'manager' ? ' selected' : '') . '>Quản lý</option>';
                    echo '<option value="superadmin"' . ($admin['role'] == 'superadmin' ? ' selected' : '') . '>Superadmin</option>';
                    echo '</select></div>';
                    echo '<button type="submit" class="btn">Cập nhật</button>';
                    echo '<a href="admins.php" class="btn">Hủy</a>';
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
document.getElementById('add-admin-btn').addEventListener('click', function() {
    document.getElementById('add-admin-form').style.display = 'block';
});
document.getElementById('cancel-add').addEventListener('click', function() {
    document.getElementById('add-admin-form').style.display = 'none';
});
</script>
