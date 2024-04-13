FROM nginx

LABEL maintainer="Boris Blagojević <boris.blagojevicc@hotmail.com>"

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf