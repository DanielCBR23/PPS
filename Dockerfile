# Dockerfile

FROM php:8.1-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar aplicação
WORKDIR /var/www
COPY . .

# Permissões
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www
