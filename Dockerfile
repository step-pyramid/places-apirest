FROM php:8.2-apache

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first
COPY composer.json composer.lock* ./

# Install PHP dependencies (FIXED - using absolute path)
RUN /usr/bin/composer install --no-dev --optimize-autoloader --no-scripts

# Copy the rest of the application
COPY . .

# Fix permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# ENABLE APACHE MODULES
RUN a2enmod rewrite && a2enmod headers

# Change document root to public folder
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set ServerName to avoid the AH00558 warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable Apache rewrite rules for front controller pattern
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf
RUN echo '    Options Indexes FollowSymLinks' >> /etc/apache2/apache2.conf
RUN echo '    AllowOverride All' >> /etc/apache2/apache2.conf
RUN echo '    Require all granted' >> /etc/apache2/apache2.conf
RUN echo '    FallbackResource /index.php' >> /etc/apache2/apache2.conf
RUN echo '</Directory>' >> /etc/apache2/apache2.conf