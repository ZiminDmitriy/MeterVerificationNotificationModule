FROM ubuntu:18.04

RUN apt-get update \
&& apt-get install -y \
    software-properties-common \
&& add-apt-repository ppa:ondrej/php \
&& apt-get update \
&& apt-get upgrade -y \
&& echo "Europe/Moscow" > /etc/localtime \
&& echo "Europe/Moscow" > /etc/timezone \
&& apt-get install -y -f \
    unzip \
    nginx \
    supervisor \
    php-pear \
    php7.4-dev \
    php7.4-fpm \
    php7.4-mysql \
    php7.4-curl \
    php7.4-amqp \
    php7.4-mbstring \
&& pecl channel-update pecl.php.net \
&& pecl install -f \
    uopz \
&& echo 'extension=uopz.so' >> etc/php/7.4/mods-available/uopz.ini \
&& phpenmod -s cli uopz \
&& curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin \
&& apt-get purge apache2* && apt-get autoremove && rm -rf /*/apache2* \
&& rm -rf /var/lib/apt/lists/* /tmp/pear /var/www/html

COPY config/php-fpm/php.ini /etc/php/7.4/fpm/php.ini
COPY config/php-fpm/php.ini /etc/php/7.4/cli/php.ini
COPY config/php-fpm/www.conf /etc/php/7.4/fpm/pool.d/www.conf
COPY config/php-fpm/php-fpm.conf /etc/php/7.4/php-fpm/php-fpm.conf
COPY config/nginx/nginx.conf /etc/nginx/nginx.conf
COPY config/nginx/default.conf /etc/nginx/sites-enabled/default
COPY config/supervisor /etc/supervisor/
