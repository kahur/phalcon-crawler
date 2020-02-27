FROM php:7.2-fpm

RUN apt-get update && apt-get install curl -y
RUN apt-get install ca-certificates apt-transport-https libssl-dev -y
RUN apt-get -y install git gcc make autoconf libc-dev pkg-config libyaml-dev -y
RUN pecl install yaml-2.0.0 && docker-php-ext-enable yaml
RUN docker-php-ext-install pdo pdo_mysql
RUN git clone --depth=1 -b 3.4.x "git://github.com/phalcon/cphalcon.git"
RUN cd cphalcon/build && ./install
RUN echo "extension=phalcon.so" >> /usr/local/etc/php/conf.d/30-phalcon.ini
RUN apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev

RUN apt-get install -y zlib1g-dev
RUN docker-php-ext-install mbstring

RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

## Install PSR module

RUN git clone https://github.com/jbboehr/php-psr.git
RUN cd php-psr && /usr/local/bin/phpize && ./configure --with-php-config=/usr/local/bin/php-config \
&& make && make test && make install

RUN echo "extension=psr.so" >> /usr/local/etc/php/conf.d/20-psr.ini

RUN mkdir /code && mkdir /code/cache && mkdir /code/cache/volt && chmod 0777 /code/cache -R
