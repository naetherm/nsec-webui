FROM ubuntu:20.04
MAINTAINER "Markus Näther <naether.markus@gmail.com>"

ENV HOME /root
ENV DEBIAN_FRONTEND noninteractive

# Use Supervisor to run and manage all other services
CMD ["/usr/local/bin/supervisord", "-c", "/etc/supervisord.conf"]

# Install required packages
RUN apt-get update && apt-get install -y \
		curl \
		libcurl4 \
		python \
		cron \
		nano \
                mcrypt \
		nginx \
		php7.4-fpm \
		php7.4-cli \
		php7.4-gd \
		php7.4-sqlite \
		php7.4-curl \
		php7.4-opcache \
		php7.4-mbstring \
		php7.4-zip \
		php7.4-xml \
                php7.4-sqlite3 \
		php-mysql \
		redis-server \
		nodejs \
                composer \
		npm


ADD . /var/www
WORKDIR /var/www

RUN touch database/database.sqlite 
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache && composer install
RUN ls ./
RUN php artisan migrate
RUN php artisan db:seed


CMD php artisan serve --host 0.0.0.0
