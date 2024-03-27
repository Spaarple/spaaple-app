FROM php:8.3-apache-bullseye

# Set working directory
WORKDIR /var/www

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y --no-install-recommends nodejs gnupg wget locales apt-utils git g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev libicu-dev chromium \
    && apt-get update \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo mysqli pdo_mysql gd opcache intl calendar dom mbstring zip xsl exif xdebug

RUN curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Add user for symfony application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

EXPOSE 9000
