ARG IMAGE_NAME=leatotech/php
ARG IMAGE_TAG=latest

FROM ${IMAGE_NAME}:${IMAGE_TAG}

ARG APP_ENV='dev'

ENV APP_ENV=${APP_ENV}

COPY ./infra/docker/php/entrypoint.sh /usr/local/sbin/entrypoint.sh
COPY ./infra/docker/php/conf.d/${APP_ENV}/*.ini /usr/local/etc/php/conf.d/
COPY ./infra/docker/nginx/*.conf /etc/nginx/http.d/
COPY ./infra/docker/supervisor/messenger.conf /etc/supervisor/conf.d/messenger-worker.conf

WORKDIR /app

COPY .env ./.env
COPY bin bin/
COPY config config/
COPY config/routes.yaml config/routes.yaml

COPY public public/
COPY src src/
COPY templates templates/
COPY vendor vendor/
COPY migrations migrations/

# RUN


RUN chmod +x /usr/local/sbin/entrypoint.sh

ENTRYPOINT ["/usr/local/sbin/entrypoint.sh"]

EXPOSE 8000
