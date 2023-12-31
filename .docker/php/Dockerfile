FROM php:8.2-fpm

WORKDIR /var/www

COPY .docker/php/php.ini /usr/local/etc/php/
COPY .docker/php/docker.conf /usr/local/etc/php-fpm.d/docker.conf
COPY .docker/php/.bashrc /root/

RUN apt-get update
RUN apt-get install -y build-essential zlib1g-dev default-mysql-client curl gnupg procps vim git unzip libzip-dev libpq-dev
RUN docker-php-ext-install zip pdo_mysql

RUN apt-get install -y libicu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# pcov
RUN pecl install pcov
RUN docker-php-ext-enable pcov

# Xdebug
# RUN pecl install xdebug
# RUN docker-php-ext-enable xdebug
# RUN echo ";zend_extension=xdebug" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Node.js, NPM
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update
RUN apt-get install nodejs -y

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
RUN composer config --global process-timeout 3600

COPY . .

# Install dependencies
# RUN composer install --no-scripts --no-interaction --no-progress --prefer-dist --optimize-autoloader
RUN composer install --optimize-autoloader --no-dev
RUN php artisan optimize:clear
RUN php artisan optimize

# RUN npm install
# RUN npm run build

RUN chown -R www-data:www-data ./storage
RUN chown -R www-data:www-data ./bootstrap

EXPOSE 5173
