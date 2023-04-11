ARG NODE_VERSION=16

FROM node:${NODE_VERSION}-alpine AS cwa_app

# Cookie Secret environment required to build nuxt application
# Other environment variables needed but are fine to include as build args as they would not be secret.

RUN apk add --no-cache \
  git \
  python3 \
  make \
  g++

RUN ln -sf python3 /usr/bin/python

WORKDIR /usr/src/app

COPY package.json yarn.* ./
RUN yarn install --network-timeout 60000

COPY . ./

EXPOSE 3000

COPY _docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start
CMD ["start"]
