FROM php:8.3-apache-bullseye

RUN apt-get update
RUN apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /var/cache/apt/archives/*

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo pdo_mysql gd opcache intl calendar dom mbstring zip gd xsl

RUN curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN chown -R 1000:1000 $(pwd)

WORKDIR /var/www/
