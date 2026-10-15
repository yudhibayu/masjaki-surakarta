<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\referencesMasjaki\models\News;
use app\modules\referencesMasjaki\models\NewsSearch;

class BeritaController extends Controller
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        $modelberita = News::find()->where(['<>', 'id', $id])->orderBy(['created_at' => SORT_DESC])->limit(10)->all();

        return $this->render('view', [
            'model' => $model,
            'url_prev' => Url::previous(),
            'modelberita' => $modelberita,
        ]);
    }
    // public function actionView($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //         'url_prev'  => Url::previous()
    //     ]);
    // }

    /**
     * Finds the News model based on its primary key value.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
