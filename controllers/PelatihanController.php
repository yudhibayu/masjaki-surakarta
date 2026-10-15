<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\construction\models\ConstructionVacancies;
use app\modules\construction\models\ConstructionVacanciesSearch;
use app\modules\construction\models\WorkTraining;
use app\modules\construction\models\WorkTrainingSearch;

class PelatihanController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
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
                'class' => \yii\filters\VerbFilter::class,
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
        \app\components\Theming::setThemes(Yii::$app->params['frontEndTheme']);
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
        $searchModel = new WorkTrainingSearch();
        $dataProviderPublicated = $searchModel->search($params, $searchModel::PUBLICATED);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProviderPublicated' => $dataProviderPublicated,
        ]);
    }

    /**
     * Displays a single ConstructionVacancies model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
    
        return $this->render('view', [
            'model' => $model,
        ]);
    }    
    // public function actionView($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);
      
    //     $modellowongan = ConstructionVacancies::find()->where(['<>', 'id', $id])->orderBy(['created_at' => SORT_DESC])->limit(10)->all();

    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //         'url_prev' => Url::previous(),
    //         'modellowongan' => $modellowongan
    //     ]);
    // }
    
    /**
     * Finds the ConstructionVacancies model based on its primary key value.
     * @param integer $id
     * @return ConstructionVacancies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkTraining::findOne($id)) !== null) {
            return $model;
        }
    
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}