FROM nginx

LABEL maintainer="Boris Blagojević <boris.blagojevicc@hotmail.com>"

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf