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

# add Nelmio Docs
composer require nelmio/api-doc-bundle

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

I've added Nelmio Docs, mapped only one response as an example:
[http://localhost/api/doc.json](Docs)

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

### Connecting to local Database
**Host:** 127.0.0.1

**Port:** 3306

**Database:** app_core_db

**User:** app_admin

**Pass:** 1cgMx56faAD8v2343Adf433x1ppW

### What can be improved?
- Normalization of Response Codes
- Validations
  - Required
  - Exists
  - Types
- Nelmio Configuration and Mapping (OpenAPI Specs)
- Global Error Handling
- Validation Errors and Error Responses
- Tests
  - With Roles and all operations
  - Error Situations
- Logs
- Montitoring / Observability

### Running it using `CURL`

#### Login

`POST http://localhost/api/login_check`
```bash
curl -X POST http://localhost/api/login_check \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -d '{"email": "sjunior.admin@gmail.com", "password": "test123"}'
```
#### List Users (role admin)
`GET http://localhost/api/admin/v1/users`
```bash
curl -X GET http://localhost/api/admin/v1/users \
 -H "Accept: application/json" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer <token_from_previous_request>"
```
**Response**
```json
{
  "data": [
    {
      "id": "27e21e47-9256-4e57-aa4c-aa33f6da7b97",
      "email": "sjunior.admin@gmail.com",
      "roles": [
        "ROLE_ADMIN",
        "ROLE_USER"
      ]
    },
    {
      "id": "e975d5ed-dcf8-4fe8-8f52-d8384bc2558a",
      "email": "sjunior.user@gmail.com",
      "roles": [
        "ROLE_USER"
      ]
    }
  ]
}
```
From here is just change the ENDPOINT and METHOD and you can test all
All Endpoints:

LOGIN `GET http://localhost/api/admin/v1/users`

ADMIN ROUTES:

List all: `GET http://localhost/api/admin/v1/users`

Create `POST http://localhost/api/admin/v1/<id>/user`

Read: `GET http://localhost/api/admin/v1/<id>/user`

Update `PUT http://localhost/api/admin/v1/<id>/user`

Delete `DELETE http://localhost/api/admin/v1/<id>/user`


USER ROUTES:

Read: `GET http://localhost/api/v1/user`

Update: `GET http://localhost/api/v1/user`


### Running it using Rest Client VSCode
Add it [here](https://github.com/Huachao/vscode-restclient)
Configuration files are:
- user-admin.http
- user.http
