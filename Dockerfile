FROM php:8.3.24-fpm
# Copy composer.lock and composer.json
#COPY composer.json /var/www/
# Set working directory
WORKDIR /var/www
# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    default-mysql-client \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
	build-essential libssl-dev

# This section is to setup node js and npm
RUN apt-get update && apt-get install -y gnupg curl \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs build-essential \
    && npm install -g npm@9.3.1

#RUN npm install -g npm@latest


RUN docker-php-ext-install bcmath

#For advanced regular expression engine
#RUN gem install oniguruma


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd  --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-configure ftp --with-openssl-dir=/usr
RUN docker-php-ext-install ftp
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy existing application directory contents

# Create a new Laravel project
RUN composer create-project laravel/laravel laravel-app


# Change ownership to www
RUN chown -R www:www /var/www

COPY . /var/www
# Copy existing application directory permissions
COPY --chown=www:www . /var/www
# Change current user to www
USER www
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
WORKDIR /var/www
#CMD bash -c "composer install"
#CMD bash -c "cp .env.example .env"

#CMD bash -c "php artisan key:generate"
#CMD bash -c "php artisan migrate"
#CMD bash -c "php artisan db:seed"
