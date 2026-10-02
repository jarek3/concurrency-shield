FROM php:8.2.9
RUN apt-get update && apt-get install -y git zip unzip
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
