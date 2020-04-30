FROM php:alpine

MAINTAINER "Markus Näther <naetherm@informatik.uni-freiburg.de>"

# Upgrade apk
RUN apk update && apk upgrade

# Install basics
RUN apk -u add git

# Install PHP extensions
ADD install_php.sh /usr/sbin/install_php.sh
ENV XDEBUG_VERSION 2.9.4
RUN /usr/sbin/install_php.sh

RUN mkdir -p /etc/ssl/certs && update-ca-certificates

ADD install_nodes.sh /usr/sbin/install_nodes.sh
RUN /usr/sbin/install_nodes.sh

COPY . /var/www
WORKDIR /var/www

EXPOSE 8000

CMD php artisan serve --host 0.0.0.0

#HEALTHCHECK --interval=1m CMD curl -f http://localhost/ || exit 1
