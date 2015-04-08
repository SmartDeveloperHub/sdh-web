FROM ubuntu:14.04
MAINTAINER Alejandro Vera De Juan <xafilox@gmail.com>

RUN apt-get update \
	&& apt-get -y install \
		apache2 \
		php5 \
		curl \
		php5-mcrypt \
		php5-json

#Configure apache	
RUN /usr/sbin/a2enmod rewrite
RUN /usr/sbin/a2enmod ssl
RUN /usr/sbin/a2ensite default-ssl
RUN service apache2 restart

#Install composer
RUN /usr/bin/curl -sS https://getcomposer.org/installer |/usr/bin/php
RUN /bin/mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

#Copy repository
RUN rm -rf /var/www/html/*
COPY . /var/www/html
RUN mv /var/www/html/.env.example /var/www/html/.env

#Install vendor dependencies of laravel
RUN cd /var/www/html && \
    composer update
RUN chown -R www-data:www-data /var/www/

RUN source /var/www/html/.env && echo "mysql-server mysql-server/root_password password $DB_PASSWORD" | debconf-set-selections
RUN source /var/www/html/.env && echo "mysql-server mysql-server/root_password_again password $DB_PASSWORD" | debconf-set-selections

RUN apt-get -y install \
		mysql-server-5.6 \
		php5-mysqlnd \
    && apt-get -y autoremove \
           && apt-get clean

# Ports
EXPOSE 80 443