FROM leandrosilva/php:7.0-apache

RUN echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf \
	&& cp /usr/src/php/php.ini-development /usr/local/etc/php/php.ini \
	&& printf '[Date]\ndate.timezone=UTC' > /usr/local/etc/php/conf.d/timezone.ini \
	&& sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/apache2.conf \
	&& sed -i 's!memory_limit = 128M!memory_limit = 768M!g' /usr/local/etc/php/php.ini

WORKDIR /var/www

VOLUME /var/www

EXPOSE 80
