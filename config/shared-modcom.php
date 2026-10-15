<?php
$shared_config = require __DIR__ . '/shared-config.php';

return [
    // ============================= Area Modules START =====================================
    'modules' => [

        /* module standard */
        'log-reader' => [
            'class' => 'app\modules\log_reader\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main',
            //'as login_filter' => UserLoginFilter::class, // to use login filter
            'aliases' => [
                'General' => '@app/runtime/logs/app.log',
                // 'Frontend' => '@frontend/runtime/logs/app.log',
                // 'Backend' => '@backend/runtime/logs/app.log',
                // 'Console' => '@console/runtime/logs/app.log',
            ],
            'defaultTailLine' => 200,
        ],
        'user' => [
            'class'               => 'infinindotech\user\Module',
            'cost'                => 12,
            'debug'               => false,
            'admins'              => ['sadmin'],
            'urlPrefix'           => 'auth',
            'enableFlashMessages' => false,
            'enableRegistration'  => $shared_config['enableRegistration'],
            //override controller's module
            'controllerMap' => [
                'admin'        => 'app\overrides\user\controllers\AdminController',
                'profile'      => 'app\overrides\user\controllers\ProfileController',
                'recovery'     => 'app\overrides\user\controllers\RecoveryController',
                'registration' => 'app\overrides\user\controllers\RegistrationController',
                'security'     => 'app\overrides\user\controllers\SecurityController',
                'settings'     => 'app\overrides\user\controllers\SettingsController',
            ],
            'modelMap' => [
                'User'    => 'app\overrides\user\models\User',
                'Profile' => 'app\overrides\user\models\Profile',
            ],
        ],
        'rbac' =>  [
            'class' => 'dektrium\rbac\RbacWebModule',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'oauth2' => [
            'class' => 'filsh\yii2\oauth2server\Module',
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24 * 1, // 1 hari
            'storageMap' => [
                'user_credentials' => 'app\overrides\user\models\User',
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true,
                    'refresh_token_lifetime' => 3600 * 24 * 30, // 30 hari
                ]
            ]
        ],
        'app_setting' => [
            'class' => 'app\modules\app_setting\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages',
                'forceTranslation' => true
            ],
        ],
        /* module standard */

        'api' => [
            'class' => 'app\modules\api\v1\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'front-map' => [
            'class' => 'app\modules\frontMap\Module',
            'layout' => '@app/themes/' . $shared_config['frontEndTheme'] . '/views/layouts/main'
        ],
        'references-masjaki' => [
            'class' => 'app\modules\referencesMasjaki\References',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'business-entity' => [
            'class' => 'app\modules\businessEntity\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'construction' => [
            'class' => 'app\modules\construction\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'manage_sot' => [
            'class' => 'app\modules\manage_sot\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'monitoring' => [
            'class' => 'app\modules\monitoring\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'technicalspec' => [
            'class' => 'app\modules\technicalspec\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'rup' => [
            'class' => 'app\modules\rup\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'references' => [
            'class' => 'app\modules\references\References',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'data-sync' => [
            'class' => 'app\modules\data_sync\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'report' => [
            'class' => 'app\modules\report\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'backups' => [
            'class' => 'app\modules\backups\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main'
        ],
        'hitCounter' => [
            'class' => 'coderius\hitCounter\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main',
            // 'controllerMap' => [
            //     'hit-counter' => 'app\overrides\hitcounter\controllers\HitCounterController'
            // ]
        ],
        'hitcounter' => [
            'class' => 'app\modules\hitcounter\Module',
            'layout' => '@app/themes/' . $shared_config['backEndTheme'] . '/views/layouts/main',
        ],

    ],
    // ============================= Area Modules END =====================================

    // ============================= Area Component START =====================================
    'components' => [
        'appsetting' => [
            'class' => 'app\components\Appsetting',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            // 'class' => 'yii\web\DbSession',
            'useCookies' => true,
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => false,
            ],
            'timeout' => 600
        ],
        'formatter' => $shared_config['formatter'],
        'i18n' => [
            'translations' => [
                'manage_*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'manage_sot' => 'manage_sot.php',
                    ],
                ],
                'user*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'user' => 'user.php',
                    ],
                ],
                'rbac*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'rbac' => 'rbac.php',
                    ],
                ],
                'monitoring' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'rbac' => 'monitoring.php',
                    ],
                ],
                'technicalspec' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'rbac' => 'technicalspec.php',
                    ],
                ],
                'rup' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'rbac' => 'rup.php',
                    ],
                ],
            ],
        ],
        //override view module
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@infinindotech/user/views' => '@app/overrides/user/views',
                    '@dektrium/rbac/views' => '@app/overrides/rbac/views',
                    '@dektrium/rbac/widgets' => '@app/overrides/rbac/widgets'
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ksrTuCqvZY4dTFgU88AwL_29JK1lcgw7',
            'enableCsrfValidation' => true,
            'csrfCookie' => [
                'httpOnly' => false,
                // 'secure' => true,
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'forceCopy' => false,
            'bundles' => [
                'dosamigos\leaflet\LeafLetAsset' => [
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'leaflet/leaflet.js',
                        'leaflet/leaflet-geoman.min.js',
                    ],
                    'css' => [
                        'leaflet/leaflet.css',
                        'leaflet/leaflet-geoman.css',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/client/auth/<action:\w+>' => 'oauth2/rest/<action>',
            ],
        ],
        'hashids' => [
            'class' => 'yiier\hashids\Hashids',
            'salt' => 'KQXTrf7(:|.@[ue<',
            'minHashLength' => 30,
            'alphabet' => '0123456789abcdefghijklmnopqrstuvwxyz'
        ],
        'hasher' => [
            'class' => 'app\components\Hasher',
        ],
        'menuFront' => [
            'class' => 'app\components\menuFront',
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
        'indodatetime' => [
            'class' => 'app\components\IndoDateTime'
        ],
        'backup' => [
            'class' => 'amoracr\backup\Backup',
            // Path for storing the backups
            'backupDir' => '@backup',
            'expireTime' => 604800,
            'databases' => ['db', 'db2'],
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
        /*         'easyAjax' => [
            'class' => 'letsjump\easyAjax\EasyAjaxBase',
            'customOptions' => [
                //see doc
            ]
        ], */
    ] // ============================= Area Component END =====================================
];
