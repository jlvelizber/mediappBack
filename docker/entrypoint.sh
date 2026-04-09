#!/bin/sh
set -e

# Keep Laravel .env aligned with Docker DB variables.
# php artisan serve may not expose all DB_* vars to worker processes.
set_or_append_env() {
  key="$1"
  value="$2"

  if [ -z "$value" ]; then
    return 0
  fi

  if awk -F= -v k="$key" '$1 == k { found=1 } END { exit !found }' .env; then
    sed -i "s|^${key}=.*|${key}=${value}|" .env
  else
    printf '%s=%s\n' "$key" "$value" >> .env
  fi
}

set_or_append_env "DB_CONNECTION" "${DB_CONNECTION:-}"
set_or_append_env "DB_HOST" "${DB_HOST:-}"
set_or_append_env "DB_PORT" "${DB_PORT:-}"
set_or_append_env "DB_DATABASE" "${DB_DATABASE:-}"
set_or_append_env "DB_USERNAME" "${DB_USERNAME:-}"
set_or_append_env "DB_PASSWORD" "${DB_PASSWORD:-}"

# Ensure Laravel writable/cache directories exist on every start.
# This prevents runtime errors like "Please provide a valid cache path."
mkdir -p \
  storage/app/private \
  storage/app/public \
  storage/logs \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

if [ "${WAIT_FOR_DB:-true}" = "true" ]; then
  echo "Waiting for database connection..."
  ATTEMPTS=0
  until php -r "
    \$host = getenv('DB_HOST') ?: 'db';
    \$port = getenv('DB_PORT') ?: '3306';
    \$db = getenv('DB_DATABASE') ?: 'mediapp';
    \$user = getenv('DB_USERNAME') ?: 'root';
    \$pass = getenv('DB_PASSWORD') ?: '';
    try {
      new PDO(\"mysql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass);
      exit(0);
    } catch (Throwable \$e) {
      exit(1);
    }
  "; do
    ATTEMPTS=$((ATTEMPTS + 1))
    if [ "$ATTEMPTS" -ge 30 ]; then
      echo "Database not reachable after ${ATTEMPTS} attempts."
      exit 1
    fi
    sleep 2
  done
  echo "Database is ready."
fi

# APP_KEY es obligatorio; sin ello Laravel responde 500 y casi no deja rastro en storage/logs.
if [ "${CREATE_APP_KEY:-false}" = "true" ]; then
  echo "Generating APP_KEY (CREATE_APP_KEY=true)..."
  php artisan key:generate --force
  php artisan config:clear
  php artisan route:clear
  php artisan cache:clear
fi

app_key_ok=false
if php -r 'exit(getenv("APP_KEY") ? 0 : 1);'; then
  app_key_ok=true
elif grep -qE '^APP_KEY=[^[:space:]]+' .env 2>/dev/null; then
  app_key_ok=true
fi
if [ "$app_key_ok" != "true" ]; then
  echo "ERROR: APP_KEY no esta definido."
  echo "  Opcion A: en el .env de la instancia, APP_KEY=base64:... (genera con: php artisan key:generate --show)"
  echo "  Opcion B: una sola vez, CREATE_APP_KEY=true en el .env y recrear el contenedor backend."
  exit 1
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "Running migrations..."
  php artisan migrate --force
fi

if [ "${RUN_STORAGE_LINK:-false}" = "true" ]; then
  php artisan storage:link || true
fi

# Artisan above runs as root; log files would be root-owned and php-fpm (www-data) could not append.
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

exec "$@"
