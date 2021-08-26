FROM php:8.0.9-cli-alpine
RUN apk --no-cache --update add \
    postgresql-dev \
    libxml2-dev
RUN docker-php-ext-install \
    pgsql \
    pdo \
    pdo_pgsql \
    simplexml
