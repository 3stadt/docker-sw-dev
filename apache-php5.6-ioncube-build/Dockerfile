FROM php:5.6-apache

ADD php/_010_php.ini /usr/local/etc/php/

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
        nano\
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql \
    && echo "sendmail_path = /usr/sbin/ssmtp -t" > /usr/local/etc/php/conf.d/sendmail.ini \
    && echo "mailhub=smtp:1025\nUseTLS=NO\nFromLineOverride=YES" > /etc/ssmtp/ssmtp.conf \
    && cd /var/www \
    && wget http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz \
    && tar zxvf ioncube_loaders_lin_x86-64.tar.gz \
    && rm ioncube_loaders_lin_x86-64.tar.gz \
    && echo "zend_extension = /var/www/ioncube/ioncube_loader_lin_5.6.so" > /usr/local/etc/php/conf.d/_000_ioncube.ini

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY xdebug/ext-xdebug.ini /usr/local/etc/php/conf.d/
COPY sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod vhost_alias
RUN a2enmod rewrite
RUN a2ensite 000-default

WORKDIR /var/www/html

RUN usermod -u 1000 www-data

RUN chown -R www-data /var/www
