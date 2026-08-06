#!/bin/sh
set -e

cd /var/www/html

echo "Esperando la base de datos..."
tries=0
until php -r "new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    tries=$((tries + 1))
    if [ "$tries" -ge 30 ]; then
        echo "No se pudo conectar a la base de datos, se continua de todas formas."
        break
    fi
    sleep 2
done

php artisan migrate --force

# Solo la primera vez: activa RUN_SEEDER=true en las variables de entorno de
# Coolify para cargar el admin y los datos de ejemplo, luego quítala.
if [ "$RUN_SEEDER" = "true" ]; then
    echo "Ejecutando seeders..."
    php artisan db:seed --force
fi

if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
