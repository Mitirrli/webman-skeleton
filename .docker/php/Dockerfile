FROM php:7.4.18-cli-alpine

# 使用国内镜像
RUN echo http://mirrors.aliyun.com/alpine/v3.12/main > /etc/apk/repositories \
    && echo  http://mirrors.aliyun.com/alpine/v3.12/community >> /etc/apk/repositorie

RUN apk add --no-cache oniguruma-dev \
    curl-dev \
    libxml2-dev \
    libpng-dev freetype \
    libpng \
    libevent-dev\ 
    libjpeg-turbo \
    freetype-dev \
    libpng-dev \
    jpeg-dev \
    libjpeg \
    libjpeg-turbo-dev \
    icu-dev \
    gcc \
    g++ \
    make \
    autoconf \
    git \
    vim

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd bcmath pdo_mysql mysqli opcache intl pcntl sockets

RUN wget https://mirrors.aliyun.com/composer/composer.phar -O /usr/local/bin/composer \
    && chmod a+x /usr/local/bin/composer \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer \
    && composer self-update --clean-backups

ENV PHPREDIS_VERSION=5.3.4
RUN wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis

ENV PHPEVENT_VERSION=3.0.3
RUN wget http://pecl.php.net/get/event-${PHPEVENT_VERSION}.tgz -O /tmp/event.tar.tgz \
    && pecl install /tmp/event.tar.tgz \
    && rm -rf /tmp/event.tar.tgz \
    && echo 'extension=event.so' > /usr/local/etc/php/conf.d/event.ini

COPY ini $PHP_INI_DIR/conf.d

WORKDIR /www

EXPOSE 8787
