FROM node:11.5-alpine

RUN mkdir -p /usr/src/admin

WORKDIR /usr/src/admin

# Prevent the reinstallation of node modules at every changes in the source code
COPY package.json yarn.lock ./

RUN apk add --no-cache --virtual .gyp \
        python \
        make \
        g++ \
	&& yarn install \
	&& apk del .gyp

COPY . ./

RUN chmod +x ./start.sh && mv ./start.sh /usr/local/bin/start

EXPOSE 3000

ENTRYPOINT ["start"]
