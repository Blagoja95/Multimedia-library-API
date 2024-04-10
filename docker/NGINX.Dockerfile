FROM nginx

LABEL maintainer="Boris Blagojević <boris.blagojevicc@hotmail.com>"

COPY ./nginx/nginx.conf /etc/nginx/nginx.conf

COPY .env /var/www/html/