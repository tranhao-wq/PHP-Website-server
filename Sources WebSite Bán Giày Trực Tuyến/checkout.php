<?php
session_start();
require_once 'includes/config.php';
$page_title = "Thanh toán";
include 'includes/header.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin giỏ hàng
$stmt = $conn->prepare("
    SELECT c.*, p.name, p.price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(count($cart_items) == 0) {
    header("Location: cart.php");
    exit();
}

// Tính tổng tiền
$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Xử lý thanh toán
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shipping_address = $_POST['shipping_address'];
    $payment_method = $_POST['payment_method'];
    
    try {
        $conn->beginTransaction();
        
        // Tạo đơn hàng
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, total, shipping_address, payment_method) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $total, $shipping_address, $payment_method]);
        $order_id = $conn->lastInsertId();
        
        // Thêm chi tiết đơn hàng
        foreach($cart_items as $item) {
            $stmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $order_id, 
                $item['product_id'], 
                $item['quantity'], 
                $item['price']
            ]);
        }
        
        // Xóa giỏ hàng
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        $conn->commit();
        
        // Thêm script để hiển thị modal thông báo và redirect
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                // Hiển thị modal
                const modal = document.createElement("div");
                modal.style.cssText = "position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.5); z-index: 1000; text-align: center;";
                modal.innerHTML = "<h3>Đặt đơn thành công!</h3><p>Đơn hàng của bạn đã được đặt thành công. Sẽ sớm chuyển hướng...</p><button id=\'closeModal\'>OK</button>";
                document.body.appendChild(modal);

                // Redirect sau 2 giây hoặc khi nhấn OK
                const closeModal = document.getElementById("closeModal");
                closeModal.addEventListener("click", function() {
                    document.body.removeChild(modal);
                    window.location.href = "order-success.php?id=' . $order_id . '";
                });
                setTimeout(function() {
                    document.body.removeChild(modal);
                    window.location.href = "order-success.php?id=' . $order_id . '";
                }, 5000);
            });
        </script>';
        exit();
    } catch(PDOException $e) {
        $conn->rollBack();
        $error = "Có lỗi xảy ra: " . $e->getMessage();
    }
}

// Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h1>Thanh toán</h1>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white"><strong>Đơn hàng của bạn</strong></div>
        <div class="card-body">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Thuộc tính</th>
                <th>Tổng</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($cart_items as $item): ?>
                <tr>
                  <td><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></td>
                  <td>
                    <?php
                      $attrs = [];
                      if (!empty($item['color'])) $attrs[] = 'Màu: ' . htmlspecialchars($item['color']);
                      if (!empty($item['size'])) $attrs[] = 'Size: ' . htmlspecialchars($item['size']);
                      echo implode('<br>', $attrs);
                    ?>
                  </td>
                  <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ</td>
                </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="2" class="text-end"><strong>Tổng cộng</strong></td>
                <td class="fw-bold text-danger"><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white"><strong>Thông tin thanh toán</strong></div>
        <div class="card-body">
          <form method="POST" action="checkout.php">
            <div class="mb-3">
              <label for="full_name" class="form-label">Họ và tên</label>
              <input type="text" id="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Điện thoại</label>
              <input type="text" id="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="shipping_address" class="form-label">Địa chỉ giao hàng</label>
              <textarea name="shipping_address" id="shipping_address" class="form-control" rows="2" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
              <label for="payment_method" class="form-label">Phương thức thanh toán</label>
              <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                <option value="Bank Transfer">Chuyển khoản ngân hàng</option>
              </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Đặt hàng</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>