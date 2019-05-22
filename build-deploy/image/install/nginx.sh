#!/usr/bin/env bash

useradd --no-create-home nginx

bash <(curl -f -L -sS https://ngxpagespeed.com/install) --nginx-version latest -a '--with-http_ssl_module --with-http_v2_module' -y

ln -s /usr/local/nginx/conf /etc/nginx
ln -s /usr/local/nginx/logs /var/log/nginx
ln -s /usr/local/nginx/sbin/nginx /usr/sbin/nginx

mkdir -p /var/www

chown -R nginx:nginx /var/www

block='
user nginx;
worker_processes 4;

events {
    worker_connections 1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;

    # All this should be "on" on production. https://t37.net/nginx-optimization-understanding-sendfile-tcp_nodelay-and-tcp_nopush.html
    sendfile        off;
    #tcp_nopush      on;
    #tcp_nodelay     on;

    keepalive_timeout  65;


    gzip on;
    gzip_disable "msie6";
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_min_length 1024;
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript text/x-js;


    pagespeed on;

    # Needs to exist and be writable by nginx.  Use tmpfs for best performance.
    pagespeed FileCachePath /var/ngx_pagespeed_cache;

    pagespeed EnableFilters responsive_images,collapse_whitespace,remove_comments,include_js_source_maps,trim_urls;
    pagespeed DisableFilters add_head;

    pagespeed PreserveUrlRelativity on;

    pagespeed ListOutstandingUrlsOnError on;

    pagespeed StatisticsPath /ngx_pagespeed_statistics;
    pagespeed GlobalStatisticsPath /ngx_pagespeed_global_statistics;
    pagespeed MessagesPath /ngx_pagespeed_message;
    pagespeed ConsolePath /pagespeed_console;
    pagespeed AdminPath /pagespeed_admin;
    pagespeed GlobalAdminPath /pagespeed_global_admin;

    pagespeed UsePerVhostStatistics on;

    pagespeed MessageBufferSize 100000;


    include /etc/nginx/conf.d/*.conf;
    include /etc/nginx/domains.d/*.conf;
}
'

echo "$block" > /etc/nginx/nginx.conf

mkdir /etc/nginx/ssl 2>/dev/null
mkdir /etc/nginx/domains.d 2>/dev/null
