<?php
$shared_modcom = require __DIR__ . '/shared-modcom.php';
$shared_config = require __DIR__ . '/shared-config.php';
$database         = require __DIR__ . '/db-production.php';

//change this!
$home_url         = 'https://masjaki.surakarta.go.id';
$web_url_text     = 'MASJAKI';
$bcc_email        = '';
$admin_email      = '';
$sender_email     = '';
$hashkey          = 'ab0afddf2e94c043a32b8f677adcsd54';
$smtp_host        = '';
$smtp_port        = '465';
// $smtp_username    = '';
// $smtp_password    = '';
$smtp_username    = 'dpuprsolo10@gmail.com';
$smtp_password    = 'jvxrdzfhuixwambf';

$config = [
    'id'        => 'basic',
    'name'      => $shared_config['name'],
    'homeUrl'   => $home_url,
    'version'   => $shared_config['version'],
    'basePath'  => dirname(__DIR__),
    'language'  => $shared_config['language'],
    'timeZone'  => $shared_config['timeZone'],
    'bootstrap' => ['log', 'oauth2', 'coderius\hitCounter\config\Bootstrap'],
    'aliases'   => [
        '@backup'   => '@app/backups',
        '@upload'   => '@app/uploads',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    //=============== Modules START =====================
    'modules' => [],
    //=============== Modules END =====================

    //=============== Components START =====================
    'components' => [
        //Primary DB
        'db' => $database['db'],
        //Secondary DB
        'db2' => $database['db2'],
        'log' => [
            'traceLevel' => YII_DEBUG ? 2 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\SyslogTarget',
                    'levels' => ['error', 'warning'],
                    'identity' => 'iksproduction-' . $home_url,
                ],
            ],
        ],
        // 'mailer' => [
        //     'class' => \yii\symfonymailer\Mailer::class,
        //     'useFileTransport' => true,
        //     'transport' => [ //ini dipakai jika nilai diatas false
        //         // 'class' => 'Swift_SmtpTransport',
        //         'host' => $smtp_host,
        //         'username' => $smtp_username,
        //         'password' => $smtp_password,
        //         'port' => $smtp_port,
        //         'encryption' => 'tls',
        //         'dsn' => 'native://default',
        //     ],
        // ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'transport' => [
                'scheme' => 'smtps',
                'host' => 'smtp.gmail.com',
                'username' => $smtp_username,
                'password' => $smtp_password,
                'port' => 465,
                // 'encryption' => 'ssl',
                // 'dsn' => 'native://default',
            ],
            // 'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'hitCounter' => [
            'class' => 'coderius\hitCounter\HitCounter',
            'tableName' => '{{%hit_counter}}',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'hostInfo' => $home_url,
            'rules' => [
                'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',
            ],
        ],
    ],
    //=============== Components END =====================

    'params' => [

        //Email Report
        'footerEmail'      => $shared_config['name'] . ' Mailing System',
        'logoEmail'        => 'images/' . $shared_config['logo_email'],
        'bccEmail'         => $bcc_email,
        'adminEmail'       => $admin_email,
        'senderName'       => $shared_config['name'] . ' Mailer',
        'senderEmail'      => $sender_email,
        'subjectEmail'     => 'Notifikasi ' . $shared_config['name'],

        //Sitewide
        'version'            => $shared_config['version'],
        'webUrlText'         => $web_url_text,
        'name'               => $shared_config['name'],
        'subtitle'           => $shared_config['subtitle'],
        'slogan'             => $shared_config['slogan'],
        'homeUrl'            => $home_url,
        'logoWeb'            => 'images/' . $shared_config['logo_web'],
        'formatter'          => $shared_config['formatter'],
        'language'           => $shared_config['language'],
        'timeZone'           => $shared_config['timeZone'],
        'subtitle'           => $shared_config['subtitle'],
        'copyright'          => '&copy; ' . $shared_config['name'],
        'enableLogin'        => $shared_config['enableLogin'],
        'enableYearLogin'    => $shared_config['enableYearLogin'],
        'enableRegistration' => $shared_config['enableRegistration'],
        'hashkey'            => $hashkey,
        'hashExpiration'     => 7,
        'api'                => $shared_config['api'],
        'frontEndTheme'      => $shared_config['frontEndTheme'],
        'backEndTheme'       => $shared_config['backEndTheme'],
        'loginTheme'         => $shared_config['loginTheme'],
        'bsVersion'          => $shared_config['bsVersion'],
        //Socials
        'socials'            => $shared_config['socials']
    ],
];

$config['modules'] = array_merge($shared_modcom['modules'], $config['modules']);
$config['components'] = array_merge($shared_modcom['components'], $config['components']);

return $config;
