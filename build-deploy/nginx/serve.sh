#!/usr/bin/env bash

PATH_SSL="/etc/nginx/ssl"
PATH_KEY="${PATH_SSL}/${1}.key"
PATH_CSR="${PATH_SSL}/${1}.csr"
PATH_CRT="${PATH_SSL}/${1}.crt"

if [ ! -f $PATH_KEY ] || [ ! -f $PATH_CSR ] || [ ! -f $PATH_CRT ]
then
  openssl genrsa -out "$PATH_KEY" 2048 2>/dev/null
  openssl req -new -key "$PATH_KEY" -out "$PATH_CSR" -subj "/CN=$1/O=Docker/C=UK" 2>/dev/null
  openssl x509 -req -days 365 -in "$PATH_CSR" -signkey "$PATH_KEY" -out "$PATH_CRT" 2>/dev/null
fi

block="
server {
    listen ${3:-80};
    listen ${4:-443} ssl http2;

    ssl_certificate     /etc/nginx/ssl/$1.crt;
    ssl_certificate_key /etc/nginx/ssl/$1.key;
    ssl_protocols       TLSv1 TLSv1.1 TLSv1.2;

    server_name $1;

    access_log /var/log/nginx/$1.access.log;
    error_log /var/log/nginx/$1.error.log;

    root $2;

    index index.html index.htm index.php;

    charset utf-8;

    client_max_body_size 100M;

    pagespeed off;

    # Ensure requests for pagespeed optimized resources go to the pagespeed handler
    # and no extraneous headers get set.
    location ~ \"\\.pagespeed\\.([a-z]\\.)?[a-z]{2}\\.[^.]{10}\\.[^.]+\" {
      add_header \"\" \"\";
    }
    location ~ \"^/pagespeed_static/\" { }
    location ~ \"^/ngx_pagespeed_beacon$\" { }

    pagespeed LoadFromFile \"$1\" \"$2\";

    # Static
    location ~* .+\.(png|jpe?g|gif|css|txt|bmp|ico|flv|swf|pdf|woff|ttf|svg|eot|otf)$ {
        expires max;
        add_header Pragma public;
        add_header Cache-Control \"public\";
        add_header Vary \"Accept-Encoding\";
    }

    # Static
    location ~* .+\.html?$ {
        if (!-f \$document_root\$uri) {
            rewrite ^ /index.php?\$args last;
        }

        expires max;
        add_header Pragma public;
        add_header Cache-Control \"public\";
        add_header Vary \"Accept-Encoding\";
    }

    # Static
    location ~* .+\.(js)$ {
        expires max;
        add_header Cache-Control \"private\";
        add_header Vary \"Accept-Encoding\";
    }

    # Main
    location / {
        if (\$request_method = \"OPTIONS\") {
            add_header \"Access-Control-Allow-Origin\" \"\$http_origin\" always;
            add_header \"Access-Control-Allow-Credentials\" true always;
            add_header \"Access-Control-Allow-Headers\" \"Cache-Control, X-Requested-With, X-Session-ID\" always;

            add_header \"Access-Control-Max-Age\" 1728000;
            add_header \"Content-Type\" \"text/plain charset=UTF-8\";
            add_header \"Content-Length\" 0;

            return 204;
        }

        try_files \$uri \$uri/ /index.php?\$args;
    }

    if (!-d \$request_filename) {
        rewrite ^/(.*)/$ /\$1 permanent;
    }

    location ~ [^/]\.php(/|$) {
        fastcgi_pass php-fpm;
        fastcgi_index index.php;

        add_header \"Access-Control-Allow-Origin\" \"\$http_origin\" always;
        add_header \"Access-Control-Allow-Credentials\" true always;
        add_header \"Access-Control-Allow-Headers\" \"Cache-Control, X-Requested-With, X-Session-ID\" always;

        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
"

FILE="/etc/nginx/domains.d/$1.conf"

if [ ! -f $FILE ]
then
    echo "$block" > $FILE
fi
