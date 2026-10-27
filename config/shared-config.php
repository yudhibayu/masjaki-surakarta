<?php

return [
    'name'               => 'MASJAKI',
    'version'            => '2.0.0',
    'subtitle'           => '',
    'slogan'             => 'Jer Basuki Mowo Beyo',
    'logo_web'           => 'logo-infinindotech.png',
    'logo_email'         => 'logo-ska.png',
    'language'           => 'id_ID',
    'timeZone'           => 'Asia/Jakarta',
    'enableRegistration' => false,
    'enableLogin'        => true,
    'enableYearLogin'    => false,
    'frontEndTheme'      => 'better_front',
    'backEndTheme'       => 'admin_core',
    'loginTheme'         => 'login',
    'formatter' => [
        'class'             => 'yii\i18n\Formatter',
        'locale'            => 'id-ID',
        'dateFormat'        => 'php:j F Y',
        'datetimeFormat'    => 'php:j F Y H:i:s',
        'timeFormat'        => 'php:H:i:s',
        'defaultTimeZone'   => 'Asia/Jakarta',
        'decimalSeparator'  => ',',
        'thousandSeparator' => '.',
        'currencyCode'      => 'IDR',
    ],
    'api' => [
        'map'  => [
            'key' => 'AIzaSyDwoxbsxIDc3iejD1So21FWy4zKJtdoR90',
            'secret' => ''
        ],
        'recaptcha' => [
            'key' => '',
            'secret' => ''
        ],
        'webmaster' => [
            'key' => '',
            'secret' => ''
        ],
    ],
    'socials' => [
        'discus'    => "",
        'google'    => "",
        'linkedin'  => "",
        'twitter'   => "",
        'facebook'  => "",
        'instagram' => "",
    ],
    'bsVersion' => '5.x',

];
