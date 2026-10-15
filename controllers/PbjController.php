<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Session;
use yii\web\Response;
use yii\web\Controller;
use app\models\LoginForm;
use app\components\Theming;
use app\models\ContactForm;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\rup\models\Pbj;
use yii\web\NotFoundHttpException;
use app\modules\rup\models\PbjSearch;

class PbjController extends Controller
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
        $params = Yii::$app->request->queryParams;
        $searchModel = new PbjSearch();
        $dataProviderTender = $searchModel->search($params, 0);
        $dataProviderNonTender = $searchModel->search($params, 1);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProviderTender' => $dataProviderTender,
            'dataProviderNonTender' => $dataProviderNonTender
        ]);
    }

    /**
     * Displays a single Pbj model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
      
     
        $modelpbj =Pbj::find()->where(['<>', 'kodelelang', $id])->orderBy(['created_at' => SORT_DESC])->limit(10)->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev' => Url::previous(),
            'modelpbj' => $modelpbj
        ]);
    }
    // public function actionView($id)
    // {
    //     $model = $this->findModel($id);

    //     // Contoh: Mendapatkan pekerjaan terbaru
    //     $latestWorksProvider = new \yii\data\ActiveDataProvider([
    //         'query' => \app\modules\rup\models\Pbj::find()->where(['<>', 'id', $id])->orderBy(['created_at' => SORT_DESC]),
    //         'pagination' => [
    //             'pageSize' => 5,
    //         ],
    //     ]);

    //     return $this->render('view', [
    //         'model' => $model,
    //         'latestWorksProvider' => $latestWorksProvider,
    //     ]);
    // }


    /**
     * Finds the Pbj model based on its primary key value.
     * @param int $id
     * @return Pbj the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pbj::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
