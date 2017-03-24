#inherit from apache and php7
FROM php:7.0-apache

RUN a2enmod rewrite
RUN a2enmod headers

COPY src/ /var/www/html/
COPY conf/headers.conf /etc/apache2/mods-enabled/


#dont output the logs to stdout/stderr
RUN rm /var/log/apache2/error.log && touch /var/log/apache2/error.log
RUN rm /var/log/apache2/access.log && touch /var/log/apache2/access.log

EXPOSE 80
