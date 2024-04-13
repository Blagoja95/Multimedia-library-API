FROM mysql:latest as mysql_dev

LABEL maintainer="Boris Blagojević <boris.blagojevicc@hotmail.com>"

COPY ./db/script /docker-entrypoint-initdb.d/