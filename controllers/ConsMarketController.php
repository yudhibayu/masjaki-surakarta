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
use app\modules\rup\models\Rup;
use yii\web\NotFoundHttpException;
use app\modules\rup\models\PbjSearch;
use app\modules\rup\models\RupSearch;
use app\overrides\user\models\LoginForm as ModelsLoginForm;
use app\modules\references\models\Year_model as Reftahun; // use Year_model module

class ConsMarketController extends Controller
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

    protected function findModel($id)
    {
        if (($model = Rup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        $searchModel = new RupSearch();
        $dataProvider = $searchModel->search($params, true);

        $sumberdanafilter = Rup::find()->select('sumberdana')->distinct()->all();
        $sumberdanafilter = ArrayHelper::map($sumberdanafilter, 'sumberdana', 'sumberdana');
        $statusumumkanfilter = Rup::find()->select('statusumumkan')->distinct()->all();
        $statusumumkanfilter = ArrayHelper::map($statusumumkanfilter, 'statusumumkan', 'statusumumkan');
        $jenispengadaan = Rup::find()->select(['idjenispengadaan', 'jenispengadaan'])->distinct()->all();
        $jenispengadaan = ArrayHelper::map($jenispengadaan, 'idjenispengadaan', 'jenispengadaan');
        if (isset($sumberdanafilter[""])) {
            unset($sumberdanafilter[""]);
        };
        if (isset($statusumumkanfilter[""])) {
            unset($statusumumkanfilter[""]);
        };
        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sumberdanafilter' => $sumberdanafilter,
            'statusumumkanfilter' => $statusumumkanfilter,
            'jenispengadaan' => $jenispengadaan,
        ]);
    }

    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $modelrup = Rup::find()->where(['<>', 'idrup', $id])->orderBy(['created_at' => SORT_DESC])->limit(10)->all();

        return $this->render('view', [
            'model1' => $this->findModel($id),
            'model' => $modelrup,
            'url_prev' => Url::previous(),
            'modelrup' => $modelrup
        ]);
    }
}
