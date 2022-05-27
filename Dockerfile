FROM openswoole/swoole
RUN docker-php-ext-install mysqli pdo_mysql
RUN apt-get -y update
RUN apt-get install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY ./www /var/www
WORKDIR /var/www
RUN composer update --no-dev