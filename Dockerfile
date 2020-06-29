FROM php:alpine3.10

MAINTAINER "Markus Näther <naetherm@informatik.uni-freiburg.de>"

# Upgrade apk
RUN apk update && apk upgrade

# Install basics
RUN apk -u add git sqlite curl
#python

# Install PHP extensions
ADD install_php.sh /usr/sbin/install_php.sh
ENV XDEBUG_VERSION 2.9.4
RUN /usr/sbin/install_php.sh

RUN mkdir -p /etc/ssl/certs && update-ca-certificates

ADD install_nodes.sh /usr/sbin/install_nodes.sh
RUN /usr/sbin/install_nodes.sh

COPY . /var/www
WORKDIR /var/www

RUN mkdir -p storage/framework
RUN mkdir storage/framework/sessions storage/framework/views storage/framework/cache
RUN composer update --no-interaction --ansi
RUN composer install --no-interaction --ansi

EXPOSE 8000

# TODO(naetherm): For testing purposes, remove in production because there we are using mysql
RUN touch database/database.sqlite && php artisan migrate

CMD php artisan serve --host 0.0.0.0

#HEALTHCHECK --interval=1m CMD curl -f http://localhost/ || exit 1
