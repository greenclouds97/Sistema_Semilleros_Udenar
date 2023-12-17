FROM php:8.1-fpm

WORKDIR /var/www/html/Sistema_Semilleros_Udenar

# Install system dependencies.
RUN apt-get update && apt-get install -y \
    libpng-dev \
    curl \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp --with->
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/loca>

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs npm

# Copy only the necessary files for Composer to install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction

# Copy the rest of the application code
COPY . .

# Optimize autoloader and run other tasks
RUN composer dump-autoload --no-scripts --optimize && \
  chmod +x /home

EXPOSE 9000


