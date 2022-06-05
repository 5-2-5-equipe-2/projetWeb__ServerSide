FROM php:8.1-apache

RUN pecl install xdebug-3.1.4 \
	&& docker-php-ext-enable xdebug

# install php extensions with docker-php-ext-install (no need for phar)
RUN docker-php-ext-install pdo pdo_mysql mysqli  \
    && docker-php-ext-enable pdo pdo_mysql mysqli

#add the rule file_uploads = On in the file php.ini
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# give all permission to the image folder
RUN chmod -R 777 /var/www/html