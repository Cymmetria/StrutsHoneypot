#inherit from apache and php7
FROM php:7.0-apache

RUN a2enmod rewrite

COPY src/ /var/www/html/
#dont output to stdout/stderr the logs.

RUN rm /var/log/apache2/error.log && touch /var/log/apache2/error.log
RUN rm /var/log/apache2/access.log && touch /var/log/apache2/access.log

EXPOSE 80
