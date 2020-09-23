# \ are new lines by the looks of things

FROM ubuntu:18.04

# this is the author
LABEL maintainer="Dan Norris"

# environment file
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    # installed to avoid ubuntu asking some interactive stuff regarding timezone setting
    && apt-get install -y gnupg tzdata \
    && echo "UTC" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

RUN apt-get update \
    && apt-get install -y curl zip unzip git supervisor sqlite3 \
    nginx php7.2-fpm php7.2-cli \
    php7.2-pgsql php7.2-sqlite3 php7.2-gd \
    php7.2-curl php7.2-memcached \
    php7.2-imap php7.2-mysql php7.2-mbstring \
    php7.2-xml php7.2-zip php7.2-bcmath php7.2-soap \
    php7.2-intl php7.2-readline php7.2-xdebug \
    php-msgpack php-igbinary \
    # installing composer here
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    # clean up operations below here
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    # this stops nginx from pushing itself to the background
    && echo "daemon off;" >> /etc/nginx/nginx.conf

# creates a sim link from stdout
# anything that it tries to write to nginx/access or nginx/error its going to write to dev/stdout and supervisor will pick it up
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# this is using the "namespace" we created when we built the shippingdocker image, e.g. docker/app/
# so it looks for default at that path then adds it to the url in the second arg
ADD default /etc/nginx/sites-available/default

# tell it to add the supervisor config file
ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# this is adding the php-fpm conf file and turned off daemonise
ADD php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf

# adds start-container and renames start-container
# then changes permissions
ADD start-container.sh /usr/bin/start-container
RUN chmod +x /usr/bin/start-container

ENTRYPOINT [ "start-container" ]