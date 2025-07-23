<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gia Dụng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header class="header">
    <div class="container">
        <div class="row align-items-center py-2">
            <!-- Logo -->
            <div class="col-md-3 d-flex align-items-center">
                <img src="images/logo.png" alt="Logo" class="logo">
            </div>

            <!-- Thanh tìm kiếm -->
            <div class="col-md-5">
                <form class="search-form d-flex">
                    <input type="text" class="form-control" placeholder="Bạn muốn tìm kiếm gì...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Thông tin hỗ trợ -->
            <div class="col-md-4 d-flex justify-content-end">
                <div class="support me-3">
                    <i class="fa fa-truck text-danger"></i>
                    <div>
                        <p class="m-0">Giao hàng</p>
                        <small>Trên toàn quốc</small>
                    </div>
                </div>
                <div class="hotline">
                    <i class="fa fa-phone text-danger"></i>
                    <div>
                        <p class="m-0 text-danger">0123 456 789</p>
                        <small>Hỗ trợ khách hàng</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">TRANG CHỦ</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link active" href="gioi_thieu.php" role="button">GIỚI THIỆU</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">SẢN PHẨM</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Sản phẩm mới</a></li>
                        <li><a class="dropdown-item" href="#">Sản phẩm khuyến mãi</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tin_tuc.php">TIN TỨC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lien_he.php">LIÊN HỆ</a>
                </li>
            </ul>

            <!-- Hiển thị tên khách hàng nếu đã đăng nhập -->
            <div class="d-flex align-items-center">
                <?php if (!empty($ten_khachhang)) { ?>
                    <span class="text-white me-3">Xin chào, <strong><?php echo htmlspecialchars($ten_khachhang); ?></strong></span>
                    <a href="profile.php" class="btn btn-success btn-sm me-2">Hồ sơ</a>
                    <a href="logout.php" class="btn btn-danger btn-sm">Đăng xuất</a>
                <?php } else { ?>
                    <a href="login.php" class="nav-link text-white me-3">
                        <i class="fa fa-user"></i> Đăng nhập
                    </a>
                <?php } ?>
                
                
                <a href="cart.php" class="nav-link text-white me-3">
                    <i class="fa fa-shopping-cart"></i> 0
                </a>
            </div>
        </div>
    </nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
