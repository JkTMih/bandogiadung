<?php
session_start();
require_once '../db_connect.php';

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Nếu người dùng nhấn nút "Mua ngay"
if (isset($_POST['buy_now'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $query_product = "SELECT * FROM products WHERE id = '$product_id'";
    $result_product = mysqli_query($conn, $query_product);

    if ($result_product && mysqli_num_rows($result_product) > 0) {
        $product = mysqli_fetch_assoc($result_product);

        // Thêm sản phẩm vào giỏ hàng trong session
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image' => $product['image']
        ];
    }
}

// Kiểm tra nếu giỏ hàng trống
if (empty($_SESSION['cart'])) {
    echo "Giỏ hàng của bạn trống. Vui lòng thêm sản phẩm vào giỏ hàng.";
    exit;
}

// Tính tổng giá trị đơn hàng
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $product) {
    $price = (float)$product['price'];
    $quantity = (int)$product['quantity'];
    $total_price += $price * $quantity;
}

// Xử lý form khi người dùng gửi yêu cầu thanh toán
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Thêm đơn hàng vào bảng orders
    $user_id = $_SESSION['user_id']; // Giả sử người dùng đã đăng nhập
    $query_order = "INSERT INTO orders (user_id, total_price, status) 
                    VALUES ('$user_id', '$total_price', 'Pending')";
    mysqli_query($conn, $query_order);
    $order_id = mysqli_insert_id($conn);

    // Thêm sản phẩm vào bảng order_items
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $price = (float)$product['price'];
        $quantity = (int)$product['quantity'];

        $query_order_items = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        mysqli_query($conn, $query_order_items);
    }

    // Thêm thông tin vận chuyển vào bảng shipping
    $tracking_number = 'TRACK' . rand(10000, 99999);
    $shipping_company = 'Giao Hàng Nhanh';
    $estimated_delivery = date('Y-m-d', strtotime('+7 days'));

    $query_shipping = "INSERT INTO shipping (order_id, tracking_number, shipping_company, estimated_delivery, status) 
                       VALUES ('$order_id', '$tracking_number', '$shipping_company', '$estimated_delivery', 'Processing')";
    mysqli_query($conn, $query_shipping);

    // Thêm thông tin thanh toán vào bảng payments
    $transaction_id = 'TRANS' . rand(10000, 99999);
    $query_payment = "INSERT INTO payments (order_id, payment_method, transaction_id, status) 
                      VALUES ('$order_id', '$payment_method', '$transaction_id', 'Pending')";
    mysqli_query($conn, $query_payment);

    // Xóa giỏ hàng
    unset($_SESSION['cart']);

    echo "<script>alert('Thanh toán thành công! Bạn sẽ được chuyển hướng về trang chủ.'); window.location.href='index.php';</script>";
    exit;
}
?>

<?php include('header.php'); ?>

<div class="checkout-container">
    <h2 class="checkout-title">Thông tin thanh toán</h2>

    <!-- Hiển thị thông tin sản phẩm -->
    <div class="checkout-products">
        <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
            <div class="product-details">
                <img src="../images/dogiadung/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                <div class="product-info">
                    <p><strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
                    <p>Giá: <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
                    <p>Số lượng: <?php echo $product['quantity']; ?></p>
                    <p>Tổng: <?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?> VNĐ</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="order-summary">
        <p><strong>Tổng đơn hàng: <?php echo number_format($total_price, 0, ',', '.'); ?> VNĐ</strong></p>
    </div>

    <!-- Form thanh toán -->
    <form action="checkout.php" method="POST" class="checkout-form">
        <div class="form-group">
            <label for="fullname">Họ và tên:</label>
            <input type="text" name="fullname" id="fullname" required class="form-control">
        </div>

        <div class="form-group">
            <label for="address">Địa chỉ giao hàng:</label>
            <textarea name="address" id="address" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="payment_method">Phương thức thanh toán:</label>
            <select name="payment_method" id="payment_method" required class="form-control">
                <option value="COD">Thanh toán khi nhận hàng</option>
                <option value="Credit Card">Thẻ tín dụng</option>
                <option value="Bank Transfer">Chuyển khoản ngân hàng</option>
                <option value="E-Wallet">Ví điện tử</option>
            </select>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" value="Xác nhận thanh toán" class="btn btn-success btn-lg">
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
