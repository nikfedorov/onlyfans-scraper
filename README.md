# Onlyfans Scraper

This is a simple project that scrapes public data from [Onlyfans](https://onlyfans.com).

## Installation

After cloning the repository run the command:

```sh
./install.sh
```

It accomplishes the following:
- install composer dependencies
- build application containers
- migrate the database
- build frontend

To delete existing volumes and rebuild containers add a `--rebuild` flag:

```sh
./install.sh --rebuild
```

## Usage

### Scrape

To scrape a single account run the following command:

```sh
./vendor/bin/sail artisan app:scrape
```

After you run it a queue job will be dispatched to run the scrape.

In order to actually handle the job make sure to run a dev script described below.

### Search

To search the database against scraped accounts run:

```sh
./vendor/bin/sail artisan app:search
```

### Search API

Together with CLI interface the application also provides a simple API endpoint to perfprm the search.

To use it go to [http://localhost/api/search?q=model](http://localhost/api/search?q=model).

You can replace `q` parameter with your desired search string.

### API docs

To make interaction with the API easier you might want to check its documentation at [http://localhost/docs/api](http://localhost/docs/api).

## Development

This repository provides a set of handy commands to streamline development workflow.

### Dev script

Run the following command in a separate terminal:

```sh
./vendor/bin/sail composer dev
```

It provides the following:
- catches application logs
- builds Vite frontend
- runs Laravel Horizon

### Check script

Whenever you finished development of a new feature run:

```sh
./vendor/bin/sail composer check
```

This script accomplishes the following:
- format code with [Laravel Pint](https://laravel.com/docs/12.x/pint)
- run the test suite demanding 100% coverage
- perform static code analysis with [Larastan](https://github.com/larastan/larastan/)
- check code style with [PHP Insights](https://github.com/nunomaduro/phpinsights)

### Tests watcher

While development run:

```sh
./vendor/bin/sail composer watch
```

This script automatically reruns the test suite whenever you change any application code.

To learn more visit [PHPUnit watcher github page](https://github.com/spatie/phpunit-watcher).

