FROM php:8.1.27-apache
RUN docker-php-ext-install mysqli
EXPOSE 	80
