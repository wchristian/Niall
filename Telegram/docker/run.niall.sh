#!/bin/bash

while true; do
  cd /app;
  sleep 5;
  mysql -h db -u steelcity -pd517nF31M1TiXVs steelcity < /app/vendor/longman/telegram-bot/structure.sql;
  php /app/hook.php;
done