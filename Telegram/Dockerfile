FROM php:7.0-cli
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /app
RUN chmod +x /app/nt
WORKDIR /app
ENTRYPOINT ["/app/nt"]