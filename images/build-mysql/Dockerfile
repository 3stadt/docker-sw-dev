FROM mariadb

RUN usermod -u 1000 mysql
RUN mkdir -p /var/run/mysqld
RUN chmod -R 777 /var/run/mysqld
RUN chmod -R 777 /etc/mysql/conf.d
RUN mkdir -p /var/log/mysql_debug && chmod -R 777 /var/log/mysql_debug