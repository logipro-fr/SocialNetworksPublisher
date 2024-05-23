FROM php:8.3.4-fpm
# Définir le fuseau horaire
ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
# Installation de l'extension pecl pcov
RUN pecl install pcov \
    && docker-php-ext-enable pcov

EXPOSE 9000
