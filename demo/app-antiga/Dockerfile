FROM php:8.3-cli-alpine

WORKDIR /app

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY ["composer.json", "composer.lock", "./"]
RUN composer install

EXPOSE 8080
CMD sh -c "composer install && composer run start"
