<?php

use coderius\hitCounter\components\deviceDetect\DeviceDetector;

// $params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db-production.php';
$shared_modcom = require __DIR__ . '/shared-modcom.php';
$shared_config = require __DIR__ . '/shared-config.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@backup'   => '@app/backups',
        '@upload'   => '@app/uploads',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [

        /*'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            ],*/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db['db'],
        'db2' => $db['db2'],
        'appsetting' => [
            'class' => 'app\components\Appsetting',
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex that used to sync queries
            // 'class' => \yii\queue\file\Queue::class,
            // 'path' => '@runtime/queue',
        ],
        'backup' => [
            'class' => 'amoracr\backup\Backup',
            // Path for storing the backups
            'backupDir' => '@backup',
            'expireTime' => 604800,
            'databases' => ['db',],
            // Directories that will be added to backup
            'directories' => [
                // format: <inner backup filename> => <path/to/dir>
                'images' => '@app/web/images',
                'uploads' => '@upload',
            ],
            // Compression
            'compression' => 'zip',
            'skipFiles' => [
                '.gitignore',
            ]
        ],


    ],
    'params' => [

        //Email Report
        // 'footerEmail'      => $shared_config['name'].' Mailing System',
        // 'logoEmail'        => 'images/'.$shared_config['logo_email'],
        // 'bccEmail'         => $bcc_email,
        // 'adminEmail'       => $admin_email,
        // 'senderName'       => $shared_config['name'].' Mailer',
        // 'senderEmail'      => $sender_email,
        // 'subjectEmail'     => 'Notifikasi '.$shared_config['name'],

        //Sitewide
        'version'            => $shared_config['version'],
        // 'webUrlText'         => $web_url_text,
        'name'               => $shared_config['name'],
        'subtitle'           => $shared_config['subtitle'],
        'slogan'             => $shared_config['slogan'],
        // 'homeUrl'            => $home_url,
        'logoWeb'            => 'images/' . $shared_config['logo_web'],
        'formatter'          => $shared_config['formatter'],
        'language'           => $shared_config['language'],
        'timeZone'           => $shared_config['timeZone'],
        'subtitle'           => $shared_config['subtitle'],
        'copyright'          => '&copy; ' . $shared_config['name'],
        'enableLogin'        => $shared_config['enableLogin'],
        'enableYearLogin'    => $shared_config['enableYearLogin'],
        'enableRegistration' => $shared_config['enableRegistration'],
        // 'hashkey'            => $hashkey,
        'hashExpiration'     => 7,
        'api'                => $shared_config['api'],
        'frontEndTheme'      => $shared_config['frontEndTheme'],
        'backEndTheme'       => $shared_config['backEndTheme'],
        'bsVersion'          => '5.x',
        //Socials
        'socials'            => $shared_config['socials']
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
    'modules' => [
        'rbac' => 'dektrium\rbac\RbacConsoleModule',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
