# POC Symfony 6
Description for the poc (to completed).

**Instructions for the construction of this repot** (to be removed in the poc): This package does not propose by default, some libraries because they do not seem useful for the majority of the pocs. However, you can install them manually if needed. Examples:

* HttpClient: `composer require symfony/http-client`
* Mailer: `composer require symfony/mailer`
* Notifier: `composer require symfony/notifier`
* TestUnits: `composer require --dev symfony/test-pack`


## Prerequisites

* The PHP version must be greater than or equal to PHP 8.2
* The SQLite 3 extension must be enabled
* The JSON extension must be enabled
* The Ctype extension must be enabled
* The date.timezone parameter must be defined in php.ini

More information on [symfony website](https://symfony.com/doc/6.2/reference/requirements.html).


## Installation
Command lines:

```bash
# clone current repot
composer install

# (optional) Copy and edit configuration values ".env.local"

php bin/console doctrine:database:create
php bin/console doctrine:database:create --connection=second
php bin/console doctrine:migrations:migrate -n


# Optional
php bin/console doctrine:fixtures:load -n
```

For the asset symlink install, launch a terminal on administrator in Windows environment.

## Usage
Just execute this command to run the built-in web server _(require [symfony installer](https://symfony.com/download))_ and access the application in your browser at <http://localhost:8000>:

```bash
# Dev env
symfony server:start

# Test env
APP_ENV=test php -d variables_order=EGPCS -S 127.0.0.1:8000 -t public/
```

Alternatively, you can [configure a web server](https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) like Nginx or Apache to run the application.

Your commit is checked by several dev tools (like phpstan, php cs fixer...). These tools were managed by [Grumphp](https://github.com/phpro/grumphp), you can edit configuration on file [grumphp.yml](./grumphp.yml) or check manually with the command: `./vendor/bin/grumphp run`.


## Development notes
For make new migration, don't use the command `php bin/console make:migration` (no compatible with DoctrineMigrationsMultipleDatabaseBundle), instead use: `php bin/console doctrine:migrations:diff`.
