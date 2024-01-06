git pull
cp .env.example .env
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan optimize
php artisan route:cache
php artisan cache:clear
php artisan migrate
