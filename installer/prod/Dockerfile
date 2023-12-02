# syntax=docker/dockerfile:1.4

FROM ghcr.io/dentest-project/api:latest AS app_main

FROM caddy:2.6-builder-alpine AS app_caddy_builder

RUN xcaddy build

FROM caddy:2.6-alpine AS app_caddy

WORKDIR /srv/app

COPY --from=app_caddy_builder --link /usr/bin/caddy /usr/bin/caddy
COPY --from=app_main --link /srv/app/public public/
COPY --link installer/prod/docker/caddy/Caddyfile /etc/caddy/Caddyfile
