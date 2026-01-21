FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
    autoconf \
    nodejs \
    npm \
    g++ \
    gcc \
    libc-dev \
    make \
    postgresql-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    icu-dev \
    libzip-dev \
    libxml2-dev

RUN docker-php-ext-install bcmath \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install intl \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install zip \
    && docker-php-ext-install soap \
    && pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

# xdebug install
# RUN pecl install xdebug-3.0.1 && docker-php-ext-enable xdebug

#RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
#  && pecl install mongodb \
#  && docker-php-ext-enable mongodb \
#  && apk del build-dependencies build-base openssl-dev autoconf \
#  && rm -rf /var/cache/apk/*

#RUN docker-php-ext-install exif && \
#     docker-php-ext-enable exif

RUN apk add git
# composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./docker/php.ini /usr/local/etc/php/conf.d/php.ini

# cron
RUN echo '* * * * * cd /var/www/html && php artisan schedule:run >&1 2>&1' > /etc/crontabs/root

RUN apk add mc
RUN apk add --no-cache tini

RUN mkdir /.config
RUN mkdir /.config/psysh
RUN chmod 0777 /.config && chmod 0777 /.config/psysh

WORKDIR /var/www/html
# ENTRYPOINT ["/sbin/tini", "--", '-u 0']
CMD ["/usr/sbin/crond", "-f"]
