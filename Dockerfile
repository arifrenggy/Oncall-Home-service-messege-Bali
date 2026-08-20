# Menggunakan base image Alpine Linux terbaru yang sangat ringan
FROM alpine:3.19

# Menginstal Apache, PHP 8.2, dan ekstensi database yang esensial
RUN apk update && apk add --no-cache \
    apache2 \
    php82-apache2 \
    php82 \
    php82-mysqli \
    php82-pdo \
    php82-pdo_mysql \
    php82-mbstring \
    php82-session

# Mengonfigurasi Apache agar membaca file index.php sebagai halaman utama
RUN sed -i 's/DirectoryIndex index.html/DirectoryIndex index.php index.html/g' /etc/apache2/httpd.conf

# Menghapus file default bawaan Apache
RUN rm -rf /var/www/localhost/htdocs/*

# Menyalin seluruh kode sistem informasi Anda ke direktori server Alpine
COPY . /var/www/localhost/htdocs/

# Menyesuaikan kepemilikan dan hak akses direktori 
RUN chown -R apache:apache /var/www/localhost/htdocs/ \
    && chmod -R 755 /var/www/localhost/htdocs/

# Mengekspos port 80
EXPOSE 80

# Perintah utama untuk menjalankan service Apache di latar depan (foreground)
CMD ["httpd", "-D", "FOREGROUND"]
