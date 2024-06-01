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

# add UUID for public IDs
composer require symfony/uid

# add JWT Auth
composer require lexik/jwt-authentication-bundle

```

---
### Commands and Run

```bash
# Run the application on Docker ([http://localhost](localhost))
docker compose up

# fixtures
php bin/console doctrine:fixtures:load --no-interaction
```
### Application Structure

```bash
src/
├── Controller
│   ├── UserAdminController.php
│   └── UserController.php
├── DataFixtures
│   └── AppFixtures.php
├── Entity
│   ├── Trait
│   └── User.php
├── Error
│   └── Normalizer.php
├── Factory
│   └── UserFactory.php
├── Kernel.php
├── Repository
│   └── UserRepository.php
├── Response
│   └── UserResponse.php
└── Service
    └── UserService.php
```

For **Authentication** I'm used `lexik/jwt-authentication-bundle` which also provides the login route `/api/login_check`.

Users are created with different roles using fixtures and will be created during container launch.
- sjunior.admin@gmail.com
- sjunior.user@gmail.com

I've decided to differentiate `admin` routes to `user` routes, so access control are simplified.

### Unified User Response
```json
{
  "data": {
    "id": "b0393b4c-d213-40fa-8a7c-ab30f5a3eb99",
    "email": "sjunior.admin@gmail.com",
    "roles": [
      "ROLE_ADMIN",
      "ROLE_USER"
    ]
  }
}
```

### Docker and tests
```bash

# run migration for test environment
docker compose exec app-default php bin/console --env=test do:mi:mi

# run phpunit
docker compose exec app-default php vendor/bin/phpunit

# run any other command
docker compose exec app-default <command>

# log into the container
docker compose exec app-default /bin/sh
```

### Rest Client VSCode
Add it [here](https://github.com/Huachao/vscode-restclient)
Configuration files are:
- user-admin.http
- user.http
