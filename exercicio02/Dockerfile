FROM php:7.4-apache

# Instalar extensão MySQL PDO
RUN docker-php-ext-install pdo pdo_mysql

# Copiar aplicação PHP
COPY app/ /var/www/html/

# Criar diretório de logs
RUN mkdir -p /var/log/apache2 && \
    chown -R www-data:www-data /var/www/html

# Expor porta padrão do Apache
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
