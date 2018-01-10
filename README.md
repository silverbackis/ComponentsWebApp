# BW Starter Website

## Introduction
This repository demonstrates a starting point to create a modern progressive web app with server-side rendering and utilising some of the best frameworks and packages available.

I've chosen to use NuxtJS (VueJS) and API Platform (build on Symfony) for many reasons. They are some of the most fun frameworks to use, built by brilliant developers and very well maintained. This combines them to show what is possible, and mostly just using the standard features without heavy customisation.

## Demo
- https://starter.b-w.uk
- https://api.starter.b-w.uk

## Installation
Simply clone or download this repository. Using Docker and Docker Compose simply run:
```bash
docker-compose up
```

The API will run on port 8080 and the front-end simply on your localhost port 80. The docker-compose.yaml and Dockerfile files have only been configured to run without SSL. You should learn Docker and change the config if you plan to use Docker in your development environment. I have only just begun using Docker when I started using API Platform - PRs are welcome.


## Commands
```bash
# Drops schema, recreates and loads fixtures
bin/console app:fixtures:load

# Updates timestamps in database to file last modified
bin/console app:form:cache:clear
```