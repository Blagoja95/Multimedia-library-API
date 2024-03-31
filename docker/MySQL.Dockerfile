FROM mysql:latest

LABEL maintainer="Boris Blagojević <boris.blagojevicc@hotmail.com>"

COPY ./db/script /docker-entrypoint-initdb.d/