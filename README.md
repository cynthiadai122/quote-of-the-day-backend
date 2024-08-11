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
cd quote-of-the-day-backend
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
HEYSAIDSO_API_KEY=API_KEY

## Step 5: Install Dependencies

```sh
composer install
```

## Step 6: Migrate and Seed Data

```sh
php artisan migrate && php artisan db:seed
```

## Step 7: Start the Laravel Development Server

```sh
php artisan serve
```

## Step 8 (optional):

## Test account:

email: test@gmail.com
password: password

if error show in home page
An error occurred while fetching the quote of the day.
this is api call only available five times per day

### option 2: run with docker

## Step 1: Update Environment Variables

DB_HOST=db

## Step 2: Build docker image

```sh
docker-compose up --build -d
```

### Run Laravel Migrations

```sh
docker-compose exec app php artisan migrate
```

### Run seed data

```sh
docker-compose exec app php artisan db:seed
```

### Start the Laravel Development Server

```sh
docker-compose exec app php artisan serve
```

### Other scripts

Run lint

```sh
./vendor/bin/pint
```

Run test

```sh
php artisan test
```
