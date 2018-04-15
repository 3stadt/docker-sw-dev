FROM jeboehm/php-nginx-base

COPY config/nginx.conf /etc/nginx
COPY config/limits.ini /usr/local/etc/php/conf.d/limits.ini
COPY config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html

RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories \
    && apk --no-cache add shadow && usermod -u 1000 www-data \
    && chown -R www-data /var/www \
    && rm /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
