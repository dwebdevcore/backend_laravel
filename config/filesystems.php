<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Path to geojson data file
    |--------------------------------------------------------------------------
    */
    'geojson_path' => env('GEOJSON_PATH', 'app/public/data.geojson'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url'    => 'http://104.248.44.122/storage'
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        's3:images' => [
            'driver' => 's3',
            'key' => env('S3_IMAGES_KEY'),
            'secret' => env('S3_IMAGES_SECRET'),
            'region' => env('S3_IMAGES_DEFAULT_REGION', 'us-east-1'),
            'endpoint' => env('S3_IMAGES_ENDPOINT'),
            'bucket' => env('S3_IMAGES_BUCKET'),
            'url' => env('S3_IMAGES_URL'),
            'use_path_style_endpoint' => env('S3_IMAGES_PATH_STYLE_ENDPOINT', false),
        ],

        'remote' => [
            'driver' => 'local',
            'root'   => 'images',
            'url'    => 'http://104.196.63.6/images'
        ]
    ],

];
