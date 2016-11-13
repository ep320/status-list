#!/bin/bash
SYMFONY_ENV=prod composer install --no-dev --optimize-autoloader
php bin/console doctrine:migrations:migrate --env=prod --no-interaction --no-debug
php bin/console statusbase:rebuild --env=prod