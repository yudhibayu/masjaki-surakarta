<?php

namespace app\modules\rup\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Satker;

use yii\web\Controller;
use app\models\UserSatker;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Response;
use yii\filters\AccessControl;
use app\modules\rup\models\Pbj;
use app\modules\rup\models\Rup;
use yii\web\NotFoundHttpException;
use app\modules\rup\models\PbjSearch;
use app\modules\rup\models\RupSearch;
use app\modules\rup\models\Rupjenispengadaan;
use app\modules\rup\models\Rupmetodepengadaan;
use app\modules\construction\models\PbjBusinessEntity;
use app\modules\referencesMasjaki\models\SourceOfFunds;
use app\modules\construction\models\PbjBusinessEntitySearch;
use app\modules\construction\models\ConstructionSafetySearch;
use app\modules\construction\models\ConstructionAccidentSearch;

/**
 * PbjController implements the CRUD actions for Pbj model.
 */
class PbjController extends Controller
{
    private $dropdown_data;
    private $sess;
    private $year;

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
                    'set-tab-pbj' => ['POST'],
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
                        'roles' => ['Administrator', 'OPD'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'set-tab-pbj', 'create-rup', 'select'],
                        'roles' => ['Tenaga Kerja', 'Badan Usaha'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
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
        if (isset($this->sess['tahun'])) {
            $this->year = $this->sess['tahun'];
        } else if (isset($this->sess['year'])) {
            if (is_array($this->sess['year']) && isset($this->sess['year']['name'])) {
                $this->year = $this->sess['year'];
            } else {
                $this->year = $this->sess['year'];
            }
        } else if (isset($this->sess['fiscal_year'])) {
            $this->year = $this->sess['fiscal_year'];
        } else {
            $this->year = (int)date('Y');
        }
    }

    /**
     * Dropdowns Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();

        //monggo
        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $satker = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id'], 'id' => Yii::$app->session['satker']['satker_id']])->orderBy('name')->all();
        } else {
            $satker = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id']])->orderBy('name')->all();
        }
        $this->dropdown_data['satker'] = ArrayHelper::map($satker, 'code', 'namecode');
        $this->dropdown_data['metodepengadaan'] = ArrayHelper::map(Rupmetodepengadaan::find()->all(), 'id', 'name');
        $this->dropdown_data['jenispengadaan'] = ArrayHelper::map(Rupjenispengadaan::find()->all(), 'id', 'name');
        $this->dropdown_data['source_of_funds'] = ArrayHelper::map(SourceOfFunds::find()->all(), 'id', 'name');
    }

    /**
     * Lists all Pbj models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['pbj'];
            if (isset($this->sess['pbj']['page']))
                $_GET['page'] = $this->sess['pbj']['page'];
        } else {
            $this->sess['pbj'] = $params;
        }

        $searchModel = new PbjSearch();
        $dataProviderTender = $searchModel->search($params, 0);
        $dataProviderNonTender = $searchModel->search($params, 1);

        if (!isset($this->sess['view_pbj']['nav'])) {
            $this->sess->set('view_pbj', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                ],
            ]);
        }

        $view_pbj = $this->sess['view_pbj'];

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProviderTender' => $dataProviderTender,
            'dataProviderNonTender' => $dataProviderNonTender,
            'view_pbj' => $view_pbj,
        ]);
    }

    public function actionCreateRup()
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['rup'];
            if (isset($this->sess['rup']['page']))
                $_GET['page'] = $this->sess['rup']['page'];
        } else {
            $this->sess['rup'] = $params;
        }

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
        return $this->render('pilih-rup', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sumberdanafilter' => $sumberdanafilter,
            'statusumumkanfilter' => $statusumumkanfilter,
            'jenispengadaan' => $jenispengadaan,
        ]);
    }

    public function actionSelect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = Rup::findOne(['idrup' => $id]);

        $pbj = new Pbj();
        $pbj->kodelelang = $model->idrup;
        $pbj->rup_code = $model->idrup;
        $pbj->name = $model->namapaket;
        $pbj->fiscal_year = $this->year;
        if ($model->metodepengadaan === 'Tender') {
            $pbj->pbj_type = 0;
        } else {
            $pbj->pbj_type = 1;
        }
        if (!$pbj->save(false)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Gagal Memilih'));
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Pekarjaan Berhasil Ditambahkan'));
        return $this->redirect(['index']);
    }

    public function actionSetTabPbj()
    {
        if (!isset($this->sess['view_pbj']['nav'])) {
            $this->sess->set('view_pbj', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                ],
            ]);
        }
        $navs = $this->sess['view_pbj']['nav'];
        $tabs = $this->sess['view_pbj']['tab'];
        $tabid = (int) Yii::$app->request->post('tab', 0);
        if ($tabid < 0 || $tabid > 1) {
            $tabid = 0;
        }
        for ($i = 0; $i < 2; $i++) {
            if ($i == $tabid) {
                $navs[$i] = 'active';
                $tabs[$i] = 'fade show active';
            } else {
                $navs[$i] = '';
                $tabs[$i] = 'fade';
            }
        }
        $this->sess->set('view_pbj', [
            'nav' => $navs,
            'tab' => $tabs,
        ]);
        // $this->sess['monitoring_view_tab']['nav'] = $navs;
        // $this->sess['monitoring_view_tab']['tab'] = $tabs;

        return $this->asJson(['status' => 'ok']);
    }

    /**
     * Displays a single Pbj model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;
        $constructionAccidentModel = new ConstructionAccidentSearch();
        $constructionAccidentDataProvider = $constructionAccidentModel->search($params, $id);

        $constructionSafetyModel = new ConstructionSafetySearch();
        $constructionSafetyDataProvider = $constructionSafetyModel->search($params, $id);

        $searchModelBusiness = new PbjBusinessEntitySearch();
        $dataProviderBusiness = $searchModelBusiness->search($params, $id);
        $existingRecord = PbjBusinessEntity::findOne(['kode_pbj' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'searchModelBusiness' => $searchModelBusiness,
            'dataProviderBusiness' => $dataProviderBusiness,

            'constructionAccidentModel' => $constructionAccidentModel,
            'constructionAccidentDataProvider' => $constructionAccidentDataProvider,

            'constructionSafetyModel' => $constructionSafetyModel,
            'constructionSafetyDataProvider' => $constructionSafetyDataProvider,
            'existingRecord' => $existingRecord,
        ]);
    }

    /**
     * Creates a new Pbj model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pbj();

        $model->fiscal_year = $this->year;

        if ($model->load(Yii::$app->request->post())) {
            $model->jenispengadaan = 'Pekerjaan Konstruksi';
            if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->kodelelang)]);
            }
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing Pbj model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing Pbj model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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

    public function actionDepdropBySatker()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];

        if (Yii::$app->request->post('depdrop_parents')) {
            $id0 = Yii::$app->request->post('depdrop_parents');
            $idsatker = $id0[0];
            $jenispengadaan = $id0[1];
            $satker = Satker::findOne(['id' => $idsatker]);
            if ($satker) {
                $list = Pbj::find()->where('kodesatker=:id', [':id' => $satker->code])->andWhere('jenispengadaan=:jenispeng', [':jenispeng' => $jenispengadaan])->andWhere(['fiscal_year' => $this->year])->orderBy('name')->all();
                $list = ArrayHelper::map($list, 'kodelelang', 'namecode');
                $selected = null;
                if ($idsatker != null && count($list) > 0) {
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

    /**
     * Finds the Pbj model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pbj the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pbj::findOne(['kodelelang' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('pbj');
        return $this->redirect(['index']);
    }
}
