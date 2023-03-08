<!-- @format -->

# Ryalize App with Slim4 Framework

[![Build Status](https://github.com/slimphp/Slim/workflows/Tests/badge.svg?branch=4.x)](https://github.com/slimphp/Slim/actions?query=branch:4.x)
[![Coverage Status](https://coveralls.io/repos/github/slimphp/Slim/badge.svg?branch=4.x)](https://coveralls.io/github/slimphp/Slim?branch=4.x)
[![Total Downloads](https://poser.pugx.org/slim/slim/downloads)](https://packagist.org/packages/slim/slim)
[![License](https://poser.pugx.org/slim/slim/license)](https://packagist.org/packages/slim/slim)

Slim is a PHP micro-framework that helps you quickly write simple yet powerful web applications and APIs.

## Installation

-   It's recommended that you use [Composer](https://getcomposer.org/) to install Slim.

```bash
$ composer install
```

-   Create `.env` file from `.env.example` to define Environment Variables.

-   Run Mirgtions & Seeders

### For Database Migrations and Seeders

```bash
$ db:boot           # Refresh DB then Run Seeders
$ db:refresh        # Drop then Create Tables
$ db:seed           # Run Seeders
$ db:migrate        # Create Tables Only
$ db:drop           # Drop Tables Only
```

For Testing

```bash
$ composer test
```

This will install Slim and all required dependencies. Slim requires PHP 7.4 or newer.
