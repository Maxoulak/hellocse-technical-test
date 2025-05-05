# hellocse-technical-test

HelloCSE Technical Test

## Prerequites

- php
- composer
- docker

## Install - Development

```shell
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

## Install - Testing

```shell
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --env=testing
./vendor/bin/sail artisan db:seed --env=testing
```

## Run

```shell
./vendor/bin/sail up -d
```

## Lint

```shell
composer lint
```

## Test

```shell
composer test
```
