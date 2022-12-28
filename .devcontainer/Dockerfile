ARG VARIANT="8.1"
FROM mcr.microsoft.com/vscode/devcontainers/php:${VARIANT}

# Symfony
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Blackfire
RUN bash -c "$(curl -L https://installer.blackfire.io/installer.sh)"
RUN sudo blackfire php:install

# Scripts
COPY init_database.sh /usr/bin/init_database.sh
RUN chmod +x /usr/bin/init_database.sh
