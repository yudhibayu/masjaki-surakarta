<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Session;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\Theming;
use app\modules\hitcounter\models\HitCounter;
use app\overrides\user\models\LoginForm as ModelsLoginForm;
use yii\helpers\ArrayHelper;
use app\modules\references\models\Year_model as Reftahun; // use Year_model module

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        $tahuns = null;
        $yearlogincheck = Yii::$app->params['enableYearLogin']; // if true, user registration button and access are enabled
        if (!Yii::$app->params['enableLogin']) {
            $this->goHome();
        }
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::class);
        // $event = $this->getFormEvent($model);
        // if ($yearlogincheck) {
        //     $tahuns = ArrayHelper::map(Reftahun::find()->where(['active_login' => 'T'])->orderBy('name')->all(), 'name', 'name');
        // }
        // $this->performAjaxValidation($model);

        Theming::setThemes(Yii::$app->params['loginTheme']);
        return $this->render('login', [
            'model'  => $model,
            // 'tahuns' => $tahuns,
            // 'module' => $this->module,
        ]);
    }
    // public function actionLogin()
    // {
    //     Theming::setThemes(Yii::$app->params['loginTheme']);

    //     if (!Yii::$app->user->isGuest) {
    //         return $this->goHome();
    //     }

    //     $model = new LoginForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->login()) {
    //         return $this->redirect(['/admin']);
    //     }

    //     $model->password = '';

    //     // $oldTheme = Yii::$app->view->theme;
    //     // Yii::$app->view->theme = null;

    //     $content = $this->render('login', [
    //         'model' => $model,
    //     ]);

    //     // // Mengembalikan tema ke nilai sebelumnya
    //     // Yii::$app->view->theme = $oldTheme;

    //     return $content;
    // }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $visitor = HitCounter::find()->select('cookie_mark')->distinct()->count();
        $hitsPerDay = HitCounter::find()->select('cookie_mark')->distinct()
            ->where(['>=', 'created_at', new \yii\db\Expression('CURDATE()')])
            ->count();
        $hitsPerMonth = HitCounter::find()->select('cookie_mark')->distinct()
            ->where(['>=', 'created_at', new \yii\db\Expression('DATE_FORMAT(NOW(), "%Y-%m-01")')])
            ->count();

        return $this->render('index', [
            'tahun' => $session['tahun'],
            'visitor' => $visitor,
            'hitsPerDay' => $hitsPerDay,
            'hitsPerMonth' => $hitsPerMonth,
        ]);
    }
}
