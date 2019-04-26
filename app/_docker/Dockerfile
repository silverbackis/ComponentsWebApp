FROM node:alpine

COPY _docker/run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

WORKDIR /usr/src/app

RUN apk --no-cache add -no-cache --virtual .gyp \
  git \
  python

COPY package.json yarn.* ./

RUN yarn install \
  && apk del .gyp

COPY . ./

CMD ["run"]
