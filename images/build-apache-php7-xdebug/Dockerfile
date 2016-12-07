FROM php:7-apache

ADD php/_010_php.ini /usr/local/etc/php/conf.d/

RUN apt-get update && apt-get install --no-install-recommends -y \
        ant \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        zip \
        zlib1g-dev \
        unzip \
        git \
        mysql-client \
        ssmtp \
        wget \
        tar \
        nano \
        libxml2 \
        libxml2-dev \
        openssl \
        libssl-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt mysqli \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install ftp \
    && docker-php-ext-install soap \
    && docker-php-ext-install pdo pdo_mysql \
    && echo "sendmail_path = /usr/sbin/ssmtp -t" > /usr/local/etc/php/conf.d/_011_sendmail.ini \
    && echo "mailhub=smtp:1025\nUseTLS=NO\nFromLineOverride=YES" > /etc/ssmtp/ssmtp.conf \
 && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN echo "date.timezone = 'Europe/Berlin'" >> /usr/local/etc/php/php.ini

COPY xdebug/ext-xdebug.ini /usr/local/etc/php/conf.d/

COPY sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY ssl/cert.txt /etc/ssl/certs/cert.txt
COPY ssl/key.txt /etc/ssl/certs/key.txt

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer

RUN a2enmod vhost_alias
RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2ensite 000-default

RUN echo -e "ENABLED=1\nIGNORE_RESOLVCONF=yes" > /etc/default/dnsmasq
RUN echo -e "nameserver 127.0.0.1\ndomain localdomain\nsearch localdomain" > /etc/resolv.conf

WORKDIR /var/www/html

RUN usermod -u 1000 www-data

RUN chown -R www-data /var/www
