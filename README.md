# Laravel Project Setup

This guide will walk you through setting up a local development environment for your Laravel project using a local MySQL database.

## Prerequisites

-   **Docker** and **Docker Compose** installed (if using Docker).
-   **MySQL** installed on your local machine.
-   **DBeaver** or another database management tool (optional).
-   **phpenv** and **php-build** for managing PHP versions.

## Step 1: Clone the Repository

```sh
git clone git@github.com:cynthiadai122/quote-of-the-day-backend.git
cd uote-of-the-day-backend
```

## Step 2: Install and Configure phpenv

Install phpenv
Follow the instructions on phpenv's GitHub page and install the php-build plugin.

## Install PHP

```sh
phpenv install 8.3.6
```

## Set Local PHP Version

```sh
phpenv local 8.3.6
```

## Verify PHP Installation

```sh
php -v
```

## Set Up phpenv Environment Variables

Add to your shell profile (~/.bashrc or ~/.zshrc):

```sh
export PHENV_ROOT="$HOME/.phpenv"
export PATH="$PHENV_ROOT/bin:$PATH"
eval "$(phpenv init -)"
```

## Reload your shell profile:

```sh
source ~/.bashrc  # or source ~/.zshrc for Zsh
```

## Step 3: Set Up the Database

## Change Permissions for Scripts

```sh
chmod u+x scripts/create_database.sh
```

## Create the Database

```sh
scripts/create_database.sh
```

## Drop the Database (if needed)

```sh
chmod u+x scripts/drop_database.sh
scripts/drop_database.sh
```

## Step 4: Configure Environment Variables

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel
DB_PASSWORD=secret

## Step 5: Install Dependencies

```sh
composer install
```

## Step 6: Start the Laravel Development Server

```sh
php artisan serve
```

### option 2: run with docker

## Step 1: Update Environment Variables

DB_HOST=db

## Step 2: Build docker image

```sh
docker-compose up --build
```

### Run Laravel Migrations

```sh
docker-compose exec app php artisan migrate
```

### Run project

```sh
docker-compose exec app php artisan migrate
```
