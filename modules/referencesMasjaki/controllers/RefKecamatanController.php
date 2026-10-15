<?php

namespace app\modules\referencesMasjaki\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKecamatanSearch;

/**
 * RefKecamatanController implements the CRUD actions for RefKecamatan model.
 */
class RefKecamatanController extends Controller
{
    private $dropdown_data;
    private $sess;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['depdrop-by-kabkota',],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->sess = Yii::$app->session;
        //monggo
    }

    /**
     * Dropdowns Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();

        $ref_kabkota_id = RefKabkota::find()->orderBy('name')->all();
        $this->dropdown_data['ref_kabkota_id'] = ArrayHelper::map($ref_kabkota_id, 'id', 'name');

        //monggo


    }

    /**
     * Lists all RefKecamatan models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['ref_kecamatan'];
            if (isset($this->sess['ref_kecamatan']['page']))
                $_GET['page'] = $this->sess['ref_kecamatan']['page'];
        } else {
            $this->sess['ref_kecamatan'] = $params;
        }

        $searchModel = new RefKecamatanSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefKecamatan model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Creates a new RefKecamatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RefKecamatan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing RefKecamatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing RefKecamatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the RefKecamatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RefKecamatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RefKecamatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('ref_kecamatan');
        return $this->redirect(['index']);
    }

    public function actionDepdropByKabkota()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];

        if (Yii::$app->request->post('depdrop_parents')) {
            $id0 = Yii::$app->request->post('depdrop_parents');
            $id = end($id0);
            $kabkota = RefKabkota::findOne(['id' => $id]);
            if ($kabkota) {
                $list = RefKecamatan::find()->where('ref_kabkota_id=:id', [':id' => $kabkota->id])->orderBy('name')->all();
                $list = ArrayHelper::map($list, 'id', 'name');
                $selected  = null;
                if ($id != null && count($list) > 0) {
                    $selected = '';
                    foreach ($list as $i => $data) {
                        $out[] = ['id' => $i, 'name' => $data];
                    }
                    if ($i == 0) {
                        $selected = $i;
                    }
                    return ['output' => $out, 'selected' => $selected];
                }
            }
        }
        return ['output' => '', 'selected' => ''];
    }
}
