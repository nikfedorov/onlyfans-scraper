# Onlyfans Scraper

This is a simple project that scrapes public data from [Onlyfans](https://onlyfans.com).

## Installation

After cloning the repository run the command:

```sh
./install.sh
```

It accomplishes the following:
- installs composer dependencies
- builds application containers
- migrates the database
- builds frontend

To delete existing volumes and rebuild containers add a `--rebuild` flag:

```sh
./install.sh --rebuild
```

## Usage

### Start

To spin up containers run:

```sh
./vendor/bin/sail up -d
```

If you just installed the appllication your containers are already running.

### Scrape

To scrape a single account run the following command:

```sh
./vendor/bin/sail artisan app:scrape
```

After you run it a queued job will be dispatched to run the scrape.

In order to actually handle the job make sure to run a dev script described below.

Once the account is scraped a new job will be pushed to the queue depending on number of account's likes:
- accounts with over 100k likes will be scraped in 24 hours
- other accounts will be scraped in 72 hours

### Search

To search the database against scraped accounts run:

```sh
./vendor/bin/sail artisan app:search
```

### Search API

Together with CLI interface the application also provides a simple API endpoint to perform the search.

To use it go to [http://localhost/api/search?q=model](http://localhost/api/search?q=model).

You can replace `q` parameter with your desired search string.

### API docs

To make interaction with the API easier you might want to check its documentation at [http://localhost/docs/api](http://localhost/docs/api).

### Stop

To stop containers run:

```sh
./vendor/bin/sail down
```

To also remove volumes add a `-v` flag:

```sh
./vendor/bin/sail down -v
```

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
- formats code with [Laravel Pint](https://laravel.com/docs/12.x/pint)
- runs the test suite demanding 100% coverage
- performs static code analysis with [Larastan](https://github.com/larastan/larastan/)
- checks code style with [PHP Insights](https://github.com/nunomaduro/phpinsights)

### Tests watcher

While development run:

```sh
./vendor/bin/sail composer watch
```

This script automatically reruns the test suite whenever you change any application code.

To learn more about it visit [PHPUnit watcher github page](https://github.com/spatie/phpunit-watcher).
