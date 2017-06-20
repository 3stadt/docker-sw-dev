FROM mariadb

RUN usermod -u 1000 mysql \
    && mkdir -p /var/run/mysqld \
    && chmod -R 777 /var/run/mysqld \
    && chmod -R 777 /etc/mysql/conf.d \
    && mkdir -p /var/log/mysql_debug && chmod -R 777 /var/log/mysql_debug