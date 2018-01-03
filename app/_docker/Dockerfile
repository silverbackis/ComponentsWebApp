FROM node:alpine

# ARG app_env
# ENV APP_ENV $app_env
# ENV NODE_ENV "development"
RUN echo $PATH

ADD . /usr/src/app/

COPY _docker/run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

WORKDIR /usr/src/app

RUN yarn install

CMD ["run"]
