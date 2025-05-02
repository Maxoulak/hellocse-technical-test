# hellocse-technical-test

HelloCSE Technical Test

## Prerequites

- php
- composer
- docker

## Install

```shell
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
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
