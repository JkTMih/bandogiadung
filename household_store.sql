-- Tạo database và chọn database để sử dụng
CREATE DATABASE IF NOT EXISTS household_store;
USE household_store;

-- Bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) UNIQUE,
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng danh mục sản phẩm
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Bảng sản phẩm
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Bảng đánh giá sản phẩm
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng giỏ hàng
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng đơn hàng
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng sản phẩm trong đơn hàng
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bảng thông tin vận chuyển
CREATE TABLE shipping (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    tracking_number VARCHAR(50) UNIQUE NOT NULL,
    shipping_company VARCHAR(100) NOT NULL,
    estimated_delivery DATE NOT NULL,
    status ENUM('Processing', 'Shipped', 'In Transit', 'Delivered') DEFAULT 'Processing',
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Bảng thanh toán
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('COD', 'Credit Card', 'Bank Transfer', 'E-Wallet') NOT NULL,
    transaction_id VARCHAR(100) UNIQUE,
    status ENUM('Pending', 'Completed', 'Failed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Bảng danh sách yêu thích
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Dữ liệu mẫu

-- Người dùng
INSERT INTO users (name, email, password, phone, address, role) VALUES
('Nguyễn Văn A', 'nguyenvana@example.com', '$2y$10$7C4rxTobHb.QijujIk7ZDOvjI9NzZMPyiPMY0vV8Id3YhSLx6tHeW', '0987654321', 'Hà Nội', 'customer'),
('Trần Thị B', 'tranthib@example.com', '$2y$10$7C4rxTobHb.QijujIk7ZDOvjI9NzZMPyiPMY0vV8Id3YhSLx6tHeW', '0912345678', 'TP.HCM', 'customer'),
('Admin', 'admin@example.com', '$2y$10$XcUhnE1hCIlU4Wv9FQ0yYeBgW2tu74yJ9uxH.0nWSR/N9rUuepOSK', '0909090909', 'Hệ thống', 'admin');


-- Danh mục
INSERT INTO categories (name, description) VALUES
('Thiết bị nhà bếp', 'Các sản phẩm gia dụng cho nhà bếp'),
('Đồ dùng phòng khách', 'Đồ dùng trang trí và tiện ích cho phòng khách'),
('Dụng cụ vệ sinh', 'Các sản phẩm giúp dọn dẹp nhà cửa'),
('Thiết bị điện gia dụng', 'Các thiết bị điện tiện lợi cho gia đình'),
('Nội thất thông minh', 'Các sản phẩm nội thất đa năng');

-- Sản phẩm
INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'Nồi cơm điện', 'Nồi cơm điện 1.8L, giữ nhiệt tốt', 590000, 50, 'noicomdien.jpg'),
(1, 'Bếp từ đôi', 'Bếp từ đôi, tiết kiệm điện', 2500000, 30, 'beptu.jpg'),
(2, 'Bộ bàn ghế sofa', 'Sofa phòng khách cao cấp', 12000000, 10, 'sofa.jpg'),
(3, 'Máy hút bụi cầm tay', 'Máy hút bụi nhỏ gọn, dễ sử dụng', 800000, 20, 'mayhutbui.jpg'),
(4, 'Quạt điều hòa', 'Quạt làm mát không khí hiệu quả', 3500000, 25, 'quatdieuhoa.jpg');

-- Đơn hàng
INSERT INTO orders (user_id, total_price, status) VALUES
(1, 590000, 'Delivered'),
(2, 12000000, 'Processing');

-- Chi tiết đơn hàng
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 590000),
(2, 3, 1, 12000000);

-- Vận chuyển
INSERT INTO shipping (order_id, tracking_number, shipping_company, estimated_delivery, status) VALUES
(1, 'TRACK12345', 'Giao Hàng Nhanh', '2025-03-10', 'Delivered'),
(2, 'TRACK67890', 'Viettel Post', '2025-03-15', 'Processing');

-- Thanh toán
INSERT INTO payments (order_id, payment_method, transaction_id, status) VALUES
(1, 'Credit Card', 'TRANS12345', 'Completed'),
(2, 'COD', NULL, 'Pending');

-- Yêu thích
INSERT INTO wishlist (user_id, product_id) VALUES
(1, 2),
(2, 4);
