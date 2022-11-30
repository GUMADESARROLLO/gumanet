FROM kooldev/php:7.4-nginx-sqlsrv-prod 

WORKDIR /app

COPY . .

RUN chmod -R 777 storage

RUN composer install --ignore-platform-reqs

EXPOSE 80