#!/usr/bin/env bash

./build-deploy/prepare.sh

# Run
echo "Run tests..."

vendor/bin/phpunit
