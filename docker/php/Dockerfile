FROM shinsenter/phpfpm-apache

ARG STABILITY="stable"
ENV STABILITY ${STABILITY}

ARG SYMFONY_VERSION=""
ENV SYMFONY_VERSION ${SYMFONY_VERSION}

ENV APACHE_DOCROOT /var/www/public
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

RUN apt-get update \
  && apt-get install -y libzip-dev acl file gettext git wget curl --no-install-recommends openssh-server \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer/composer:2-bin /composer /usr/bin/composer

RUN docker-php-ext-install \
    pdo pdo_mysql

ENV WEBHOME="/var/www/html"
ENV APACHE_DOCUMENT_ROOT="/public"
WORKDIR $WEBHOME

VOLUME ["/var/www/html"]

COPY apache/000-default.conf /etc/apache2/sites-enabled/

RUN echo "installing supervisord"
RUN apt update && apt install -y supervisor

COPY ./supervisord/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./supervisord/producers.conf /etc/supervisor/conf.d/producers.conf
COPY ./supervisord/consumers.conf /etc/supervisor/conf.d/consumers.conf