FROM node:9-alpine

# While we have a git dep and the latest mysql authentication plugins are not supportred in the official repo
RUN apk add --no-cache git

# ARG app_env
# ENV APP_ENV $app_env
# ENV NODE_ENV "development"
RUN echo $PATH

COPY _docker/run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

WORKDIR /usr/src/app

COPY package.json yarn.lock ./

RUN yarn install --production

COPY . ./

CMD ["run"]
