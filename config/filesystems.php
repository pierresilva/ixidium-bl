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

    'default' => env('BASELINE_FILESYSTEM_DRIVER', 'local'),

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

    'cloud' => env('BASELINE_FILESYSTEM_CLOUD', 's3'),

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
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('BASELINE_APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        'uploads' => [
            'driver' => 'local',
            'root' => base_path() . '/public/uploads',
            'url' => env('BASELINE_APP_URL') . '/uploads',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('BASELINE_AWS_ACCESS_KEY_ID'),
            'secret' => env('BASELINE_AWS_SECRET_ACCESS_KEY'),
            'region' => env('BASELINE_AWS_DEFAULT_REGION'),
            'bucket' => env('BASELINE_AWS_BUCKET'),
            'url' => env('BASELINE_AWS_URL'),
        ],

        'sftp' => [
            'driver'   => 'sftp',
            'host'     => env('BASELINE_FTP_HOST'),
            'username' => env('BASELINE_FTP_USERNAME'),
            'password' => env('BASELINE_FTP_PASSWORD'),
            'port'     => env('BASELINE_FTP_PORT'),
            'root'      => '',
            'passive'  => true,
            'ssl'      => true,
            'timeout'  => 30,
        ],

    ],

];
