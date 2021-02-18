php bin/console doctrine:database:create
php bin/console make:user
php bin/console make:entity Name
php bin/console doctrine:schema:update --force