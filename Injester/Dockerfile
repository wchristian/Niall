FROM php:7.0-cli
RUN apt-get update && \
    apt-get -yq install \
        libpspell-dev && \
    docker-php-ext-install pspell && \
    rm -r /var/lib/apt/lists/*

COPY . /app
RUN chmod +x /app/injester
WORKDIR /app
ENTRYPOINT ["/app/injester"]