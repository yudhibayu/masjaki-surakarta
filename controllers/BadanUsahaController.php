<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\businessEntity\models\BusinessEntitySearch;
use app\components\Theming;

class BadanUsahaController extends Controller
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
        $searchModel = new BusinessEntitySearch();
        $dataProviderVerified = $searchModel->search($params, $searchModel::VERIFICATION);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProviderVerified' => $dataProviderVerified,
        ]);
    }

    /**
     * Displays a single BusinessEntity model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        $modelbadanusaha = BusinessEntity::find()
            ->where(['<>', 'id', $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('view', [
            'model' => $model,
            'url_prev' => Url::previous(),
            'modelbadanusaha' => $modelbadanusaha,
        ]);
    }

    /**
     * Finds the BusinessEntity model based on its primary key value.
     * @param int $id
     * @return BusinessEntity|null
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessEntity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
