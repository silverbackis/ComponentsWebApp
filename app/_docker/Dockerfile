FROM node:alpine

# ARG app_env
# ENV APP_ENV $app_env
# ENV NODE_ENV "development"
RUN echo $PATH



COPY _docker/build.sh /usr/local/bin/build
RUN chmod +x /usr/local/bin/build

COPY _docker/run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

COPY package.json yarn.lock ./

RUN yarn install

COPY . /usr/src/app/

WORKDIR /usr/src/app
CMD ["build"]

# Expose port 3000 which the app runs on
EXPOSE 3000

CMD ["run"]
