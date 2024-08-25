FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies


RUN apt-get update && apt-get install -y \
    git \
    curl \
    procps \
    libpng-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    libgd-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    iputils-ping \
    zip \
    libreoffice \
    && docker-php-ext-install zip


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Cambiar los permisos del directorio, si es necesario


# Ejecutar como usuario root (o cambiar a usuario no root aquí si es necesario)

# Comando para ejecutar cron
#CMD ["cron", "-f"]

# Install PHP extensions
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user



# Set working directory https://stackoverflow.com/questions/48619445/permission-denied-error-using-laravel-docker
WORKDIR /var/www

#RUN mkdir /var/www/public/tmp/
RUN chown -R  $user:$user /var/www/
#drwxr-xr-x  2 robin    robin    4096 Oct 24 16:56  tmp
COPY php.ini /usr/local/etc/php/php.ini-production
COPY php.ini /usr/local/etc/php/php.ini
#COPY robin-cre.json /var/www/robin-cre.json
COPY php-fpm.conf /usr/local/etc/php-fpm.conf
COPY cacert.pem /etc/ssl/cacert.pem
#COPY entrypoint.bash /usr/sbin
USER root
#CMD cron -f
#RUN chmod -R 777 /var/run/
#RUN chmod gu+rw /var/run
#RUN chmod gu+s /usr/sbin/cron
#RUN touch /var/log/cron.log
#RUN chmod -R 777 /etc/cron.d
#RUN chmod -R 777 /etc/crontab
#RUN echo "* * * * *  /usr/bin/php /var/www/artisan robin:model-bot-scheduled-queries >> /var/log/cron.log 2>&1" | crontab -
#RUN echo #!/bin/bash >> /home/robin/script.sh
#RUN echo export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin >> /home/robin/script.sh
#RUN echo "php /var/www/artisan robin:model-bot-scheduled-queries >> /var/log/cron.log 2>&1" >> /home/robin/script.sh
#RUN chmod 777 /home/robin/script.sh
#RUN echo "* * * * *  /home/robin/script.sh" | crontab -
#RUN export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
#CMD cron &
USER $user
#CMD cron &&
#RUN chmod a+x /usr/sbin/entrypoint.bash
#ENTRYPOINT /usr/sbin/entrypoint.bash