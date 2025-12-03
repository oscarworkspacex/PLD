#!/bin/bash

# Iniciar cron
cron

# Iniciar supervisord
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf


# Iniciar php-fpm en primer plano
# exec php-fpm
