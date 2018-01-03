FROM node:alpine

ARG app_env
ENV APP_ENV $app_env
ENV NODE_ENV "development"

RUN echo "App Dockerfile Environment ${APP_ENV}"

# Change directory so that our commands run inside this new directory
WORKDIR /usr/app

# Get all the code needed to run the app
COPY package.json yarn.lock ./

# Install all dependencies
RUN yarn install

# Application files needed for build (pages/layout etc.) - Copy the application
COPY . .

# Build the app
RUN if [ "${APP_ENV}" = "production" ]; then yarn run build; fi

# Expose port 3000 which the app runs on
EXPOSE 3000

# Serve the app
CMD if [ "${APP_ENV}" = "production" ]; then yarn run start; else yarn run dev; fi
