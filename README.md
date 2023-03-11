<!-- @format -->

# Ryalize App with Slim4 Framework

[![Build Status](https://github.com/slimphp/Slim/workflows/Tests/badge.svg?branch=4.x)](https://github.com/slimphp/Slim/actions?query=branch:4.x)

Slim is a PHP micro-framework that helps you quickly write simple yet powerful web applications and APIs.

## Installation

-   It's recommended that you use [Composer](https://getcomposer.org/) to install Slim.

```bash
$ composer install
```

-   Create `.env` file from `.env.example` to define your Environment Variables.

-   Run Migrations & Seeders using the following commands.

### For Database Migrations and Seeders

```bash
$ composer db:boot           # Drop & Migrate & Seed
$ composer db:refresh        # Drop & Migrate
$ composer db:drop           # Drop
$ composer db:migrate        # Migrate
$ composer db:seed           # Seed
```

For Testing

define your `env` variables settings inside `phpunit.xml` then run:

```bash
$ composer test
```

Made With Love &hearts;
