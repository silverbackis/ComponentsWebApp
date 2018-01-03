FROM node:alpine

# ARG app_env
# ENV APP_ENV $app_env
# ENV NODE_ENV "development"
RUN echo $PATH

COPY _docker/run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

WORKDIR /usr/src/app

COPY package.json yarn.lock ./

RUN yarn install

COPY . ./

CMD ["run"]
