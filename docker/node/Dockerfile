FROM node:latest

LABEL maintainer="Chris Fidao"

# nodejs images are built off of debian distro of linux for some reason
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb http://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    # installed git because base container didn't come with git
    && apt-get install -y git yarn \
    # clean up again
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*