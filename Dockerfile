FROM php:7.2-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
WORKDIR ${APACHE_DOCUMENT_ROOT}

# install dependencies
RUN apt-get update && apt-get install -y \
    cron \
    libpng-dev \
    zlib1g-dev
# setup apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
# install php extensions
RUN docker-php-ext-install \
    gd \
    pdo_mysql \
    zip
# install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# setup application files
COPY ./src/ /var/www/html/

#RUN chmod 777 -R /var/www/html/bootstrap/cache/
RUN chmod 777 -R /var/www/html/storage/