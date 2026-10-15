<?php

return [
    'db' => [//Main DB
        // 'class' => 'yii\db\Connection',
        // 'dsn' => 'mysql:host=127.0.0.1;dbname=c1masjaki',
        // 'username' => 'c1masjaki',
        // 'password' => 'rnVx7TV@4',
        // 'charset' => 'utf8mb4',

        //'class' => 'yii\db\Connection',
        //'dsn' => 'mysql:host=127.0.0.1;port=3308;dbname=c1masjaki',
        //'username' => 'root',
        //'password' => '',
        //'charset' => 'utf8mb4',
        
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=153.92.15.41;port=3306;dbname=u445882797_c1masjaki',
        'username' => 'u445882797_masjaki',
        'password' => '5pQ=;g1xQJsz',
        'charset' => 'utf8mb4',

        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
        //'enableQueryCache' => true,
        //'queryCacheDuration' => 5, // five seconds
    ],
    'db2' => [//Secondary DB
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=c1masjaki',
        'username' => 'c1masjaki',
        'password' => 'rnVx7TV@4',
        'charset' => 'utf8mb4',

        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
        //'enableQueryCache' => true,
        //'queryCacheDuration' => 5, // five seconds
    ],
    'db3' => [//additional DB
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=XXX',
        'username' => 'XXX',
        'password' => 'XXX',
        'charset' => 'utf8mb4',

        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
        //'enableQueryCache' => true,
        //'queryCacheDuration' => 5, // five seconds
    ],
];
