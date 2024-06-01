#!/bin/sh

echo "App ENV: $APP_ENV"
echo "App Name: $APP_NAME"

# MIGRATION
php bin/console doctrine:migrations:migrate --no-interaction

# CLEAR CACHE
rm -fR var/cache/*; \
    mkdir -p var/cache var/cache/prod var/log var/sessions public/build/images; \
    chmod -R 0777 var/ public/ var/cache/

# SETUP MESSENGER TRANSPORTS (you will see the following messages in the first execution only)
# [OK] The "async" transport was set up successfully.
# [OK] The "failed" transport was set up successfully.
php bin/console messenger:setup-transports

if [ "$APP_ENV" == "dev" ]
then
    php bin/console doctrine:fixtures:load --no-interaction
    php bin/console lexik:jwt:generate-keypair
fi

# NGINX
/usr/sbin/nginx -g 'daemon off;' &

# SUPERVISOR
sleep 1

/usr/bin/supervisord --nodaemon --configuration /etc/supervisor/conf.d/messenger-worker.conf &

sleep 1

/usr/local/sbin/php-fpm &
PID=$!

# wait for php-fpm process to die as docker stop sends kill signals very quickly
for sig in SIGINT SIGTERM SIGKILL SIGHUP; do
    trap "echo \"$sig received for process $PID\"; kill -$sig $PID" $sig
done

# run tail in background as we need wait to be executed
# if wait goes before tail then tail never gets executed before trap
# /usr/bin/tail -f $LOG_STREAM &

wait $PID
# Second `wait` is crucial. See [12.2.2. How Bash interprets traps](http://tldp.org/LDP/Bash-Beginners-Guide/html/sect_12_02.html)
wait $PID
