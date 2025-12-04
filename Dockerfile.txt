FROM php:8.2-apache

# Instalar dependencias necesarias
RUN docker-php-ext-install pdo_mysql

# Instalar xdebug para mejorar var_dump()
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configurar xdebug para mostrar var_dump() con formato
RUN echo "xdebug.mode=develop" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.var_display_max_depth=5" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.var_display_max_children=256" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.var_display_max_data=1024" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Permitir que .htaccess sobreescriba directivas
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Ajustar el nivel de reporte de errores de PHP
RUN echo "error_reporting = E_ALL & ~E_NOTICE" > /usr/local/etc/php/conf.d/error-level.ini && \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/error-level.ini