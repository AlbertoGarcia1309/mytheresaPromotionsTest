FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/* && curl -sS https://get.symfony.com/cli/installer | bash \
                                       && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony  # Limpiar la cache de apt para reducir el tamaño de la imagen

RUN docker-php-ext-install pdo pdo_mysql

RUN useradd -m myuser

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER myuser


WORKDIR /var/www/html

COPY --chown=myuser:myuser . .

RUN composer install --no-scripts --no-interaction

RUN composer require symfony/maker-bundle --dev
RUN composer require doctrine/doctrine-fixtures-bundle --dev


EXPOSE 9000
CMD ["symfony", "server:start", "--no-tls"]

CMD ["sh", "-c", "php bin/console doctrine:migrations:migrate -n && php bin/console doctrine:fixtures:load -n && php-fpm"]

CMD ["php-fpm"]
