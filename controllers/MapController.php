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
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringTaskLocations;

class MapController extends Controller
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/bare.php';
        if(isset(Yii::$app->session['tahun'])){
            $year = Yii::$app->session['tahun'];
        }
        else{
            $year = date('Y');
        }
        $geojsons = [];
        $locations = MonitoringTaskLocations::find()->joinWith(['task'])->where([MonitoringTask::tableName().'.fiscal_year' => $year])->asArray()->all();
        foreach ($locations as $location) {
            $geojsons[] = $location['geojson'];
        }
        return $this->render('index',['tahun' => $year,'locations' => $geojsons]);
    }

}
