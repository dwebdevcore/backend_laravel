#!/usr/bin/env bash

# Run
echo "Running..."

/usr/sbin/php-fpm

nginx -g 'daemon off;'
