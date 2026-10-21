<?php
namespace app\components;

use Yii;
use yii\web\NotFoundHttpException;

class MenuFront
{
	/**
     * Set Menu
     *
     * @return string
     */
    public static function setMenu($theme_name)
    {
        $moduleUser = \Yii::$app->getModule('user'); // find user module setting
        $moduleYear = \Yii::$app->getModule('references'); // find user module setting
        $regcheck = $moduleUser->enableRegistration; // if true, user registration button and access are enabled
        $logincheck = Yii::$app->params['enableLogin'];
        
        $menuFront = '';
        if ($theme_name) {
            $menuFront = [
                'options' => ['class' => 'align-items-center'],
                'items' => [
                    ['label' => Yii::t('app', 'Home'), 'url' => ['/'], 'template' => '<a href="{url}">{label}</a>', 'class' => 'active'],
                    ['label' => Yii::t('app', 'Construction Work'), 'url' => ['/rup/pbj']],
                    ['label' => 'Peraturan Jasa Konstruksi', 'url' => ['/peraturan-jasa-konstruksi']],
                    ['label' => 'Rekap Sertifikasi TKK', 'url' => ['/rekap-sertifikasi-tkk']],
                    ['label' => Yii::t('app', 'Construction Market'), 'url' => ['/construction/construction-market']],
                    ['label' => Yii::t('app', 'Business Entity'), 'url' => ['/business-entity/business-entity']],
                    // ['label' => Yii::t('app', 'Map'), 'url' => ['/front-map'], 'template' => '<a href="{url}">{label}</a>', 'visible' => true ],
                    // ['label' => Yii::t('app', 'Table'), 'url' => ['/front-map/default/table-index'], 'template' => '<a href="{url}">{label}</a>', 'visible' => true ],
                    // ['label' => Yii::t('app', 'Admin Area'), 'url' => ['/admin'], 'template' => '<a href="{url}">{label}</a>', 'visible' => Yii::$app->user->can('Administrator')],
                    [
                        'label' => Yii::t('app', 'Setting'),
                        'url' => ['/#'],
                        'template' => '<a href="{url}"><span>{label}</span><i class="bi bi-chevron-down"></i></a>',
                        'visible' => Yii::$app->user->can('Super Administrator'),
                        'items' => [
                            ['label' => Yii::t('app', 'Global Application Setting'), 'url' => ['/app_setting'], 'visible' => Yii::$app->user->can('Administrator')],
                            ['label' => Yii::t('user', 'User Setting'), 'url' => ['/user/admin'], 'visible' => Yii::$app->user->can('Super Administrator')],
                            // ['label' => $moduleYear->t('year_lang', 'Fiscal Year Setting'), 'url' => ['/references/year'], 'visible' => Yii::$app->user->can('Super Administrator')],
                        ],
                        'options' => ['class' => 'dropdown'],
                    ],

                    Yii::$app->user->isGuest ?
                        ['label' => Yii::t('user', 'Sign In'), 'url' => ['/user/security/login'], 'visible' => $logincheck] :
                        // ['label' => Yii::t('user', 'Sign In'), 'url' => ['/site/login'], 'visible' => $logincheck] :
                        [
                            'label' => Yii::t('app', 'Utility'),
                            'url' => ['/#'],
                            'template' => '<a href="{url}"><span>{label}</span><i class="bi bi-chevron-down"></i></a>',
                            'items' => [
                                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile'], 'visible' => !Yii::$app->user->isGuest],
                                [
                                    'label' => Yii::t('user', 'Sign Out ({0})', Yii::$app->user->identity->username), 'url' => ['/user/security/logout'],
                                    'template' => '<a data-method="post" href="{url}">{label}</a>',
                                ]
                            ],
                            'options' => ['class' => 'dropdown'],
                        ],
                    ['label' => Yii::t('user', 'Register'), 'url' => ['/account-request/request'], 'template' => '<a href="{url}">{label}</a>', 'visible' => Yii::$app->user->isGuest],

                ],
                'submenuTemplate' => "\n<ul>\n{items}\n</ul>\n",
                'activateParents' => true,
            ];
        }

    return $menuFront;
    }

}
