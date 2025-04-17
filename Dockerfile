# Use the official PHP image with Apache
FROM php:8.3-apache

# Set the working directory
WORKDIR /var/www/html

# Install PostgreSQL PDO extension requirements
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Install PostgreSQL client tools
RUN apt-get update && apt-get install -y postgresql-client

# Copy the application source code to the container
COPY src/ /var/www/html/

# Copy the entrypoint script to the container
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Ensure the script has execution permissions
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80 for the Apache server
EXPOSE 80

# Set the entrypoint script
ENTRYPOINT ["bash", "/usr/local/bin/entrypoint.sh"]
