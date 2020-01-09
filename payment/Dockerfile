FROM webdevops/php-nginx:7.2
ARG APP_ENV=production
ENV APP_ENV "$APP_ENV"
ENV fpm.pool.clear_env no
ENV fpm.pool.pm=ondemand
ENV fpm.pool.pm.max_children=50
ENV fpm.pool.pm.process_idle_timeout=10s
ENV fpm.pool.pm.max_requests=500
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1

COPY ./config/nginx/global/*.conf /opt/docker/etc/nginx/conf.d/
COPY ./config/nginx/vhost/*.conf /opt/docker/etc/nginx/vhost.common.d/

WORKDIR /tmp
RUN apt-get update && apt-get -y install procps bsdtar libaio1

# Install Composer
RUN wget -O composer-setup.php --progress=bar:force https://getcomposer.org/installer && \
    php composer-setup.php --install-dir=/usr/bin --version=1.6.3 && \
    rm -f composer-setup.php

# Run APP
COPY --chown=1000:1000 ./src /app
WORKDIR /app
RUN chmod 777 -R storage
RUN chown -R www-data:www-data storage
RUN if [ "$APP_ENV" = "development" ]; then composer install; else composer install --no-dev --optimize-autoloader; fi

