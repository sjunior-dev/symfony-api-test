# Symfony API

Bootstrapping.

```shell
composer create-project symfony/skeleton:"7.0.*" symfony-api-test

# install dev dependencies
composer require --dev doctrine/doctrine-fixtures-bundle phpunit/phpunit symfony/browser-kit symfony/css-selector symfony/debug-bundle symfony/maker-bundle symfony/phpunit-bridge symfony/stopwatch symfony/web-profiler-bundle zenstruck/foundry

# add doctrine migrations
composer require doctrine/doctrine-migrations-bundle

# add symfony messenger for async
composer require symfony/messenger symfony/doctrine-messenger

# add security recipe
composer require security

```

Run the application on Docker ([http://localhost](localhost))
```
docker compose up
```

