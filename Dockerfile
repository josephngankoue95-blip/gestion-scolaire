FROM richarvey/nginx-php-fpm:3.1.6

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN mkdir -p /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache

COPY nginx.conf /etc/nginx/sites-available/default.conf

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]