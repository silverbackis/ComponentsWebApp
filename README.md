# BW Starter Web App

## Introduction
This repository demonstrates a starting point to create a modern progressive web app with server-side rendering and utilising some of the best frameworks and packages available.

I've chosen to use NuxtJS (VueJS) and API Platform (build on Symfony) for many reasons. They are some of the most fun frameworks to use, built by brilliant developers and very well maintained. This combines them to show what is possible, and mostly just using the standard features without heavy customisation.

## Demo
- https://starter.b-w.uk
- https://api.starter.b-w.uk

## Requirements
- Docker
- Docker Compose

## Installation
Simply clone or download this repository, then run:
```bash
make env
```
You can now modify the following environment files:
- /.env
- /shared/.env
- /app/.env
- /api/.env

There is a command `make php` which is for an empty Symfony application in the `api` directory. It will install the composer application and when all the files have appeared in your directory and the Symfony Flex scripts have been run, you can press any key to stop that operation before running the application as described below.

To run in development mode:
```bash
make start
```
Or in production:
```bash
make start env=prod
```

_Please note: the env=prod flag refers to which docker composer file to use and does not adjust the configuration of your front-end application or API - the application's environment for both the API and front-end application can be adjusted in their own .env files_

The production environment should also be running nginx as a proxy and you can configure the domain names and more in `./docker-compose-prod.yaml`.

In development, the API will run on port 8080 and the front-end simply on your localhost port 80.

**Docker images are available [here](https://hub.docker.com/u/silverbackis/)**

## Commands
```bash
# Drops schema, recreates and loads fixtures
bin/console app:fixtures:load

# Updates timestamps in database to file last modified
bin/console app:form:cache:clear

docker-compose exec php bin/console cache:warmup
docker-compose exec php bin/console app:fixtures:load -e dev

# Generate keys for the login. The password entered when prompted should match that of your .env file
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

## Production Docker Compose Nginx Proxy
To easily support SSL and multiple applications running on a single server you can use the setup as seen on another repository [here](https://github.com/silverbackis/DockerComposeNginxProxy.git)

## Packages used for this web app
This application is build using a number of repositories. You can visit each one to find out more.
1. API - This uses [API Component Bundle](https://github.com/silverbackis/ApiComponentBundle) which is built on [API Platform](https://api-platform.com/)
2. Front-end - This uses 4 NPM packages which will allow different CSS frameworks and components to be added in future. All the NPM packages are namespaced under [@bwstarter and available in their own repository](https://github.com/silverbackis/bwstarter_nuxt_modules)

## Adding/Modifying Components
By default the [API Component Bundle](https://github.com/silverbackis/ApiComponentBundle) comes with commonly used pre-configured entities. The [@bwstarter/bulma](https://github.com/silverbackis/bwstarter_nuxt_modules/tree/master/bulma) Nuxt Module has components and is preconfigured to match them by name to the entities from your API. You can override any component in the @bwstarter/bulma module. The default configuration is
```js
...
{
  pagesDepth: 5,
  components: {
    Hero: '~/.nuxt/bwstarter/bulma/components/Hero/Hero.vue',
    Content: '~/.nuxt/bwstarter/bulma/components/Content/Content.vue',
    Tabs: '~/.nuxt/bwstarter/bulma/components/Nav/Tabs/Tabs.vue',
    Menu: '~/.nuxt/bwstarter/bulma/components/Nav/Menu/Menu.vue',
    Form: '~/.nuxt/bwstarter/bulma/components/Form/Form.vue',
    FeatureColumns: '~/.nuxt/bwstarter/bulma/components/Feature/Columns/FeatureColumns.vue',
    FeatureStacked: '~/.nuxt/bwstarter/bulma/components/Feature/Stacked/FeatureStacked.vue',
    FeatureTextList: '~/.nuxt/bwstarter/bulma/components/Feature/TextList/FeatureTextList.vue',
    Gallery: '~/.nuxt/bwstarter/bulma/components/Gallery/Gallery.vue',
    Collection: '~/.nuxt/bwstarter/bulma/components/Collection/Collection.vue'
  }
}
...
```

Modify this to suit your needs in the nuxt.config.js file. For example:
```js
module.exports = {
  ...
  bwstarter: {
    pagesDepth: 3,
    components: {
      Hero: '~/components/CustomHero.vue',
      ...
    }
  }
  ...
}
```
The example above will modify the component used for the `Hero` component entity in your API.

## Why
With the rise of front-end frameworks and decoupled applications, I wanted to create a group of applications that could bring all the latest web technologies together and provide a starting point for any web application. As all the frameworks I use are Open Source and I owe most of my knowledge to superb free and open source projects, this is also free and open-source.

## Contributing
Contributions to this library and the other packages used are greatly appreciated. Please raise issues and Pull Requests on GitHub for the respective parts of this application. I'd be very grateful for anyone who is able to spend a little time writing up tests.
