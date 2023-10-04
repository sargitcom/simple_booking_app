FROM php:8.2-apache as first_build

RUN apt-get update

RUN apt-get install -y git

RUN docker-php-ext-install sockets

FROM first_build as second_build

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

FROM second_build as third_build

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


FROM third_build as fourth_build

RUN apt-get install -y nano

RUN apt-get install -y librabbitmq-dev

RUN pecl install amqp

RUN echo "extension=amqp.so" >> /usr/local/etc/php/php.ini-development
RUN echo "extension=amqp.so" >> /usr/local/etc/php/php.ini-production
RUN echo "extension=amqp.so" >> /usr/local/etc/php/conf.d/docker-php-ext-sockets.ini


FROM fourth_build as fifth_build

RUN a2enmod rewrite
RUN apt-get install -y libcurl4-openssl-dev curl
RUN docker-php-ext-install curl


FROM fifth_build as sixth_build

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

FROM sixth_build as seventh_build

RUN a2enmod headers

FROM seventh_build as eigth_build

RUN pecl install xdebug && docker-php-ext-enable xdebug

FROM eigth_build as ninth_build

RUN pecl install redis && docker-php-ext-enable redis