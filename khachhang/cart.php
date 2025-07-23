<?php
session_start();
include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../csskhachhang/styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <h2>Giỏ Hàng Của Bạn</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Giỏ hàng của bạn đang trống.</p>
        <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $product):
                    $price = round((float)$product['price'], 0);
                    $quantity = (int)$product['quantity'];
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="../images/dogiadung/<?php echo htmlspecialchars($product['image']); ?>" width="50"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo number_format($price, 0, ',', '.'); ?> VNĐ</td>
                        <td>
                            <form action="update_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" class="form-control d-inline-block w-50">
                                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                            </form>
                        </td>
                        <td><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</td>
                        <td><a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn btn-danger btn-sm">Xóa</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php $_SESSION['cart_total'] = $total; ?>

        <h4>Tổng tiền: <strong><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</strong></h4>
        <a href="checkout.php" class="btn btn-success">Thanh toán</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
