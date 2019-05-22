#!/usr/bin/env bash

# Create environment file if doesn't exists
php -r "file_exists('.env') || copy('.env.example', '.env');"

# Install PHP Packages
echo "Install packages..."

composer install

# Check if ready
./build-deploy/wait-for-it.sh mysql.app.local:3306 -s -t 60 -- echo "MySQL is ready." && \
./build-deploy/wait-for-it.sh elasticsearch.app.local:9200 -s -t 60 -- echo "Elasticsearch is ready." && \
./build-deploy/wait-for-it.sh localstack.app.local:4572 -s -t 60 -- echo "AWS S3 is ready." && \
./build-deploy/wait-for-it.sh localstack.app.local:4576 -s -t 60 -- echo "AWS SQS is ready." && \
./build-deploy/wait-for-it.sh localstack.app.local:4579 -s -t 60 -- echo "AWS SES is ready." && \

# Run custom commands
for file in build-deploy/run/*; do
    [ -f "$file" ] && [ -x "$file" ] && "$file"
done
