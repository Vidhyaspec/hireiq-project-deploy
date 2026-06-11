FROM php:8.2-apache

RUN docker-php-ext-install mysqli

COPY backend/ /var/www/html/

RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

EXPOSE 80