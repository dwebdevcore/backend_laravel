<?php

require_once __DIR__ . '/../vendor/autoload.php';

$s3Client = new Aws\S3\S3Client([
    'version' => 'latest',
    'region' => 'us-east-1',
    'endpoint' => 'http://localstack.app.local:4572',
    'use_path_style_endpoint' => true
]);

try {
    $response = $s3Client->createBucket([
        'Bucket' => 'images',
        'acl' => 'public-read',
    ]);

    dump($response);
} catch (Exception $e) {
    dump($e->getMessage());
}
