FROM serversideup/php:8.4-cli-alpine

USER root

# Install system packages (supervisor) and PHP extensions all at once
RUN apk add --no-cache supervisor && \
    install-php-extensions gd bcmath exif intl

# Copy the application files into the image
COPY --chown=www-data:www-data . /var/www/html

# Run composer directly in the main stage
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction

# Tell www-data processes where home is, so libpq finds ~/.postgresql correctly
ENV HOME=/var/www

# Create the SSL dir that libpq will look for
RUN mkdir -p /var/www/.postgresql && \
    chown -R www-data:www-data /var/www/.postgresql && \
    chmod 700 /var/www/.postgresql


# Copy your supervisor configuration
COPY ci_config/supervisor/conf.d/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


ENV PHP_OPCACHE_ENABLE=1

# Run Supervisor as root so it can manage processes
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
