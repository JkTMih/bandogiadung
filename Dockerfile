FROM php:8.2-apache

# Copy toàn bộ mã nguồn vào container
COPY . /var/www/html/

# Chuyển file từ thư mục public ra ngoài để Apache chạy
RUN mv /var/www/html/public/* /var/www/html/ && rm -r /var/www/html/public

# Mở cổng Apache
EXPOSE 80
