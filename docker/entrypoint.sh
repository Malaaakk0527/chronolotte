#!/bin/sh
set -e

cd /var/www/html

# Migrations au premier démarrage (idempotent)
if [ "${MIGRATE_ON_START:-true}" = "true" ]; then
    echo "=== Migration de la base ==="
    php artisan migrate --force --no-interaction
fi

exec /usr/bin/supervisord -c /etc/supervisor.d/conf.d/supervisord.conf
