FROM node:alpine

ARG app_env
ENV APP_ENV $app_env

RUN echo "App Dockerfile Environment ${APP_ENV}"

# Create a directory where our app will be placed
RUN mkdir -p /usr/src/app

# Change directory so that our commands run inside this new directory
WORKDIR /usr/src/app

# Get all the code needed to run the app
COPY package.json yarn.lock ./

# Install all dependencies
RUN yarn install

# Build the app
RUN if [ "${APP_ENV}" = "production" ]; then yarn run build; fi

COPY . ./

EXPOSE 3000

# Serve the app
CMD if [ "${APP_ENV}" = "production" ]; then yarn run start; else yarn run dev; fi
