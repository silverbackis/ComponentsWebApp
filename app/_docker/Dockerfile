FROM node:alpine

ARG app_env
ENV APP_ENV $app_env
ENV NODE_ENV "development"

ADD . /usr/src/app/
WORKDIR /usr/src/app
RUN yarn install

# Build the app
RUN if [ "${APP_ENV}" = "production" ]; then yarn run build; fi

# Expose port 3000 which the app runs on
EXPOSE 3000

# Serve the app
CMD if [ "${APP_ENV}" = "production" ]; then yarn run start; else yarn run dev; fi
