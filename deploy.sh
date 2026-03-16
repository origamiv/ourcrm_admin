whoami
ls -la
echo '------------ php'
/usr/bin/php -v
echo '------------ composer'
/usr/bin/php /usr/local/bin/composer install
echo '------------ npm'
npm run build
