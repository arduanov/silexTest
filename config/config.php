<?php

$root_path = realpath(__DIR__ . "/..");

return [
    'debug' => false,
    'root.path' => $root_path,
    'cache.path' => $root_path . '/var/cache',
    'monolog.level' => Monolog\Logger::DEBUG,
//
//    'monolog.config' => [
//        'monolog.logfile' => $root_path . '/var/logs/' . date('Y-m-d') . '.log',
//        'monolog.level' => Monolog\Logger::DEBUG,
//        'monolog.name' => 'application',
//        'monolog.slack.key' => '',
//    ],

    'migrations' => [
        'directory' => $root_path . '/src/Data/Migrations',
        'name' => 'Migrations',
        'namespace' => 'App\Data\Migrations',
        'table_name' => 'migration_versions',
    ],
    'cache.lifetime' => 0,

    'swiftmailer.options' =>
        [
            'host' => 'mailtrap.io',
            'port' => 2525,
            'username' => '',
            'password' => '',
            'encryption' => null,
        ],
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'silex',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    ]
];
