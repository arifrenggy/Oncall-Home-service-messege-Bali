# Menggunakan base image resmi PHP versi 8.2 dengan Apache
FROM php:8.5-apache

# Mengaktifkan mod_rewrite Apache (wajib jika sistem Anda menggunakan file .htaccess untuk routing)
RUN a2enmod rewrite

# Menginstal ekstensi database yang umum digunakan pada pemrograman PHP native
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Menyalin seluruh file kode program web ke direktori root Apache
COPY . /var/www/html/

# Mengatur kepemilikan dan hak akses direktori agar server dapat mengeksekusi dan menulis file (esensial untuk fitur upload)
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Mengekspos port 80 untuk menerima trafik HTTP
EXPOSE 80
