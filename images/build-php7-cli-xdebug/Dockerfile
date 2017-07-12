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
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY xdebug/ext-xdebug.ini /usr/local/etc/php/conf.d/

RUN echo "date.timezone = 'Europe/Berlin'" >> /usr/local/etc/php/php.ini \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/local/bin/composer \
    && wget https://phar.phpunit.de/phpunit.phar \
    && chmod +x phpunit.phar \
    && mv phpunit.phar /usr/local/bin/phpunit \
    && useradd dev \
    && mkdir -p /home/dev/bin \
    && chown -R dev: /home/dev

ENV PATH /home/dev/bin:$PATH
ENV HOME /home/dev

USER dev

WORKDIR /home/dev

RUN git clone https://github.com/shopwareLabs/sw-cli-tools.git \
    && cd sw-cli-tools \
    && composer install \
    && ln -s /home/dev/sw-cli-tools/bin/sw /home/dev/bin/sw \
    && mkdir -p /home/dev/.config/sw-cli-tools \
    && composer global require hirak/prestissimo

WORKDIR /var/www/html