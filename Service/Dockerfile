FROM php:7.0-apache
RUN apt-get update && \
    apt-get -yq install \
        libpspell-dev && \
    docker-php-ext-install mysqli pdo pdo_mysql pspell && \
    rm -r /var/lib/apt/lists/*

RUN a2enmod rewrite
COPY . /app
RUN rm -R /var/www/html && ln -s /app/public /var/www/html
WORKDIR /app