whoami
ls -la
echo '------------ php'
/usr/bin/php -v
echo '------------ composer'
/usr/bin/php /usr/local/bin/composer install
echo '------------ migrate'
/usr/bin/php artisan migrate
#/usr/bin/php artisan migrate:fresh --seed
/usr/bin/php artisan db:seed
#/opt/php83/bin/php artisan migrate
echo '------------ swagger'
/usr/bin/php artisan scramble:export --path=public/docs/openapi.json
# /usr/bin/php artisan scribe:generate
# /opt/php83/bin/php artisan l5-swagger:generate
