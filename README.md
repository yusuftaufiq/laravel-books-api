<h1 align="center"> Laravel Books API </h1>

<p align="center">
    <a href="https://github.com/yusuftaufiq/laravel-books-api/actions">
        <img alt="GitHub Workflow Status" src="https://img.shields.io/github/workflow/status/yusuftaufiq/laravel-books-api/Run%20Laravel%20Tests%20&%20Lint%20Codebase?style=for-the-badge">
    </a>
    <a href="https://github.com/yusuftaufiq/laravel-books-api/blob/main/LICENSE">
        <img alt="GitHub" src="https://img.shields.io/github/license/yt2951/laravel-books-api?style=for-the-badge">
    </a>
</p>

## Introduction
This app provides a list of books in a RESTful API. Source of data obtained from [Gramedia](https://ebooks.gramedia.com) by using the [web scraping](https://en.wikipedia.org/wiki/Web_scraping) technique.

## Purpose
Apart from providing a booklist API, this app was created primarily to learn how to write unit-testable code in Laravel and also presents use cases of framework features such as:
- [Cache Control](https://laravel.com/docs/9.x/responses#cache-control-middleware)
- [Eloquent](https://laravel.com/docs/9.x/eloquent)
    - [API Resources](https://laravel.com/docs/9.x/eloquent-resources)
    - [Mutators](https://laravel.com/docs/9.x/eloquent-mutators)
    - [Query Scopes](https://laravel.com/docs/9.x/eloquent#query-scopes)
- [Error Handling](https://laravel.com/docs/9.x/errors)
- [Facades](https://laravel.com/docs/9.x/facades)
- [Form Request Validation](https://laravel.com/docs/9.x/validation#form-request-validation)
- [Sanctum](https://laravel.com/docs/9.x/sanctum)
- [Service Providers](https://laravel.com/docs/9.x/providers)
- [Testing](https://laravel.com/docs/9.x/testing)
    - [Database Testing](https://laravel.com/docs/9.x/database-testing)
    - [HTTP Tests](https://laravel.com/docs/9.x/http-tests)
    - [Mocking](https://laravel.com/docs/9.x/mocking)

If you are interested in exploring this app, you can check to start from [`routes/api.php`](./routes/api.php), [`app/`](./app/) folder & [`tests/`](./tests/) folder.

## Further reading
In addition to the [official documentation](https://laravel.com/docs/9.x/) from Laravel, here are some reference articles that help the development of this application and may be of interest to you:
- https://ashallendesign.co.uk/blog/how-to-make-your-laravel-app-more-testable
- https://www.larashout.com/creating-custom-facades-in-laravel
- https://timacdonald.me/using-laravels-policies-route-model-binding-without-eloquent/

## API documentation
You can read the API documentation on the following page https://documenter.getpostman.com/view/14291055/UVyoVcj5.

## Installation
### Manual installation
Requirements: PHP 8.1, Composer, RDBMS (such as: MySQL, SQLite, PostgreSQL, etc).

Installation steps:
- Clone this repository `git clone https://github.com/yusuftaufiq/laravel-books-api.git`
- Change directory `cd laravel-books-api`
- Copy environment file `cp .env.example .env`
- Set the database configuration you are using in `.env`
- If you don't have SQLite installed, also set the database configuration in the file `.env.testing`
- Make sure you have created the database according to the `DB_DATABASE` environment value you set
- Install composer dependencies `composer install`
- Run the migration using `php artisan migrate`
- Run the application using `php artisan serve`

### Via Docker
Requirements: Docker

Installation steps:
- Clone this repository `git clone https://github.com/yusuftaufiq/laravel-books-api.git`
- Change directory `cd laravel-books-api`
- Copy environment file `cp .env.example .env`
- You may want to change `DOCKER_FORWARD_*` in `.env` to prevent port conflicts
- Build container with `docker-compose up -d --build site`
- Install composer dependencies `docker-compose run --rm composer install`
- Run the migration using `docker-compose run --rm artisan migrate`

## Useful commands
- `composer run code-analyze`: run static code analyzer using [PHPStan](https://github.com/phpstan/phpstan) and check code style using [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer)
- `php artisan test --testsuite=Unit`: run unit tests
- `php artisan test --testsuite=Feature`: run feature tests
- `php artisan responsecache:clear && php artisan cache:clear`: clear cache completely

## Limitation
Since this app is currently hosted on Heroku using a [free plan](https://www.heroku.com/free), there is no guarantee that this app will be accessible at any time.

## Tech stack
- [**Laravel 9**](https://laravel.com/docs/9.x/) - Core framework
- [**PHP 8.1**](https://www.php.net/releases/8.1/en.php) - Language syntax
- [**Docker**](https://www.docker.com/) - Container platform
- [**Github Actions**](https://docs.github.com/en/actions) - CI/CD platform

## Credits
- Greatly inspired by https://github.com/guillaumebriday/laravel-blog
- Thanks to https://github.com/aschmelyun/docker-compose-laravel for the `docker-compose` configuration
- Index page created with https://devdojo.com/tails/app

## License
This application is licensed under the [MIT license](http://opensource.org/licenses/MIT).
