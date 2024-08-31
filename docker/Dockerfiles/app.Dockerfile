FROM php:8.2.5-fpm-alpine3.17
ARG UID
RUN apk --update add shadow
RUN usermod -u $UID www-data && groupmod -g $UID www-data
RUN apk --update add sudo
RUN echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
RUN apk --update add composer
RUN apk add --no-cache freetype libjpeg-turbo libpng \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS freetype-dev libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql \
    && apk del .build-deps
    
RUN apk add --update npm
RUN apk add --update make
RUN apk --update add php-session
RUN apk --update add php-tokenizer
RUN apk --update add php-xml
RUN apk --update add php-dom
RUN apk --update add php-xmlwriter
RUN apk --update add php-fileinfo
RUN apk --update add php-gd
RUN apk --update add php-simplexml
RUN apk --update add php-xmlreader
    