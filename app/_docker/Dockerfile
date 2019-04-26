FROM node:11-alpine

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

CMD ["run"]
