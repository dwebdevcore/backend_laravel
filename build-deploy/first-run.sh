#!/usr/bin/env bash

# Generate Key
php artisan key:generate

# Add Seed Data
php artisan db:seed

# Passport install
php artisan passport:install --force

# Elasticsearch create indexes
php artisan elastic:create-index 'App\Elastic\Configurators\Business'
php artisan elastic:create-index 'App\Elastic\Configurators\BusinessAttribute'
php artisan elastic:create-index 'App\Elastic\Configurators\BusinessReview'
php artisan elastic:create-index 'App\Elastic\Configurators\BusinessPost'

# Update cover photo for businesses
php artisan update:place-avatar
php artisan update:business-score

# Cleanup
php artisan business:fix-utf8
php artisan business:fix-html-entities
php artisan business:generate-geo

# Import into ES
php artisan scout:import 'App\Models\Business'
php artisan scout:import 'App\Models\BusinessAttribute'
php artisan scout:import 'App\Models\BusinessReview'
php artisan scout:import 'App\Models\BusinessPost'

# Generate Swagger docs
php artisan l5-swagger:generate

# Authorize AWS SES
/root/.local/bin/aws --endpoint-url=http://localstack.app.local:4579 ses verify-email-identity --email-address hello@app.local

# Create SQS Queues
/root/.local/bin/aws --endpoint-url=http://localstack.app.local:4576 sqs create-queue --queue-name MailQueue
/root/.local/bin/aws --endpoint-url=http://localstack.app.local:4576 sqs create-queue --queue-name SmsQueue

# Create S3 Buckets
#php ./build-deploy/aws-s3.php
/root/.local/bin/aws --endpoint-url=http://localstack.app.local:4572 s3api create-bucket --bucket images
/root/.local/bin/aws --endpoint-url=http://localstack.app.local:4572 s3api put-bucket-acl --bucket images --acl public-read
