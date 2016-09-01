FROM php:7-cli

ADD php/_010_php.ini /usr/local/etc/php/conf.d/

RUN apt-get update && apt-get install --no-install-recommends -y \
        openjdk-7-jre-headless \
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
        wget \
        curl \
        libcurl4-gnutls-dev \
        tar \
        nano \
        libxml2 \
        libxml2-dev \
        libssl-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install ftp \
    && docker-php-ext-install soap \
    && docker-php-ext-install curl \
    && docker-php-ext-install pdo pdo_mysql

RUN echo "date.timezone = 'Europe/Berlin'" >> /usr/local/etc/php/php.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer

RUN useradd dev
RUN mkdir -p /home/dev/bin

ENV PATH /home/dev/bin:$PATH
ENV HOME /home/dev

RUN wget http://shopwarelabs.github.io/sw-cli-tools/sw.phar \
    && mv sw.phar /home/dev/bin/sw
RUN chmod +x /home/dev/bin/sw

RUN chown -R dev: /home/dev
USER dev

WORKDIR /var/www/html