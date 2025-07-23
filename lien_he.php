<?php
session_start();
include 'header.php';
?>

<div class="container" style="max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1);">
    <h2>Liên hệ với chúng tôi</h2>

    <p>Nếu bạn có bất kỳ câu hỏi nào về sản phẩm, đơn hàng hoặc dịch vụ, hãy điền thông tin vào biểu mẫu dưới đây. Chúng tôi sẽ phản hồi trong thời gian sớm nhất!</p>

    <form method="POST" action="gui_lien_he.php" style="margin-top: 20px;">
        <label for="name">Họ và tên:</label><br>
        <input type="text" name="name" id="name" required style="width: 100%; padding: 10px; margin-bottom: 15px;"><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required style="width: 100%; padding: 10px; margin-bottom: 15px;"><br>

        <label for="message">Nội dung:</label><br>
        <textarea name="message" id="message" rows="5" required style="width: 100%; padding: 10px; margin-bottom: 15px;"></textarea><br>

        <button type="submit" style="padding: 10px 20px; background-color: #2c3e50; color: #fff; border: none; border-radius: 5px;">Gửi liên hệ</button>
    </form>

    <hr style="margin: 40px 0;">

    <h3>Thông tin liên hệ</h3>
    <p><strong>Địa chỉ:</strong> 123 Đường Nhà Bếp, Quận 5, TP. Hồ Chí Minh</p>
    <p><strong>Email:</strong> lienhe@giadungvn.vn</p>
    <p><strong>Hotline:</strong> 0909 123 456</p>
    <p><strong>Thời gian làm việc:</strong> 8h00 - 17h30 (Thứ 2 đến Thứ 7)</p>
</div>

<?php include 'footer.php'; ?>
