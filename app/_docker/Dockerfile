FROM node:11-alpine

ARG API_HOST
ARG COOKIE_SECRET
ARG API_URL='http://varnish'
ARG NODE_ENV='development'
ARG HOST='0.0.0.0'
ENV API_URL_BROWSER="https://${API_HOST}"

COPY _docker/run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

WORKDIR /usr/src/app

RUN apk add --no-cache \
  git \
  python \
  make \
  g++

COPY package.json yarn.* ./

RUN yarn install

COPY . ./

RUN yarn run build

CMD ["run"]
