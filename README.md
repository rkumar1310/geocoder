# GeoCoder API

API for serve requests originating from customer mobile application and website. This API is built using **Slim 4** micro framework with **Doctrine** ORM.


# Installation

Demand API is configured to run using docker. To install API you will need to install [docker](https://www.docker.com/) and [docker compose](https://docs.docker.com/compose/). 
In order to install and run the application please execute, 

```docker-compose up```

## Run Migrations

```docker-compose run php-cli vendor/bin/doctrine orm:schema:update --force```

After installation the API will be available at http://localhost

## Folder Structure

    .
    ├── .docker                 # Docker build configuration
    ├── app                     # Application Source Files
    │   └── module-2            # Module 2 eg, geocode
    ├── build                   # Scripts for auto db migrations and seeding
    ├── db                      # Database Assets
    │   ├── schema.yml          # Database Migrations
    │   ├── migrations          # Database schema
    │   └── models           	# Database models
    ├── public                  # Web folder
    ├── .env.example           	# Enviornment specific config file example
    ├── composer.json           # Composer dependencies
    ├── docker-compose.yml      # Docker configuration
    └── README.md

> Use short lowercase names at least for the top-level files and folders except
> `README.md`

## Composer
```docker-compose run composer <command>```

## Doctrine
```docker-compose run php-cli vendor/bin/doctrine```

## Migrations
```docker-compose run php-cli vendor/bin/doctrine orm:schema:update --force```

## PHP Commands
```docker-compose run php-cli php --version```
## Migrations

We use doctrine migration package for handling migrations, [Check it out](https://www.doctrine-project.org/projects/migrations.html). 


## Seeding

We use doctrine fixtures package for handling DB seeding, [Check it out](https://github.com/doctrine/data-fixtures). 

## Publishing Code
TBA