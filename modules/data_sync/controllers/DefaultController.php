<?php

namespace app\modules\data_sync\controllers;

use app\modules\app_setting\models\App_setting;
use app\modules\data_sync\models\SyncLog;
use app\modules\data_sync\models\SyncLogSearch;
use app\queues\QueueSyncBudgetRupPenyedia;
use app\queues\QueueSyncBudgetRupSwakelola;
use app\queues\QueueSyncNonTenderPBJ;
use app\queues\QueueSyncRupKegiatan;
use app\queues\QueueSyncRupPenyedia;
use app\queues\QueueSyncRupProgram;
use app\queues\QueueSyncRupSwakelola;
use app\queues\QueueSyncTenderPBJ;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `data_sync` module
 */
class DefaultController extends Controller
{
    private $dropdown_data;
    private $session;
    private $tahun;
    private $year;
    private $kode_kota;
    private $kode_kota_pbj;

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
                        'roles' => ['Administrator'],
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
        $this->session = Yii::$app->session;
        if (isset($this->session['tahun'])) {
            $this->year = $this->session['tahun'];
        } else if (isset($this->session['year'])) {
            if (is_array($this->session['year']) && isset($this->session['year']['name'])) {
                $this->year = $this->session['year'];
            } else {
                $this->year = $this->session['year'];
            }
        } else if (isset($this->session['fiscal_year'])) {
            $this->year = $this->session['fiscal_year'];
        } else {
            $this->year = (int)date('Y');
        }
        $this->tahun = $this->year;

        try {
            $this->kode_kota = App_setting::getValue('kode_kota_rup');
            $this->kode_kota_pbj = App_setting::getValue('kode_kota_pbj');
        } catch (\Throwable $th) {
            $this->kode_kota = 'D147';
            $this->kode_kota_pbj = '104';
        }
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
    }

    /**
     * Lists all SyncLog models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = Yii::$app->session['sync_log'];
            if (isset(Yii::$app->session['sync_log']['page']))
                $_GET['page'] = Yii::$app->session['sync_log']['page'];
        } else {
            Yii::$app->session['sync_log'] = $params;
        }

        $searchModel = new SyncLogSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRupPenyedia($url = null)
    {
        if (!$url) {
            // $url = 'https://isb.lkpp.go.id/isb/api/ddb82f8d-68d6-4a51-ac39-fa0ff95c5f73/json/163364579/PaketPenyediaOpt1618/tipe/4:12/parameter/';
            $url = 'https://isb.lkpp.go.id/isb-2/api/4eb7e158-259e-4a6a-9e20-fdf43ca9f678/json/15105/RUP-PaketPenyedia-Terumumkan/tipe/4:12/parameter/';
        }
        Yii::$app->queue->push(new QueueSyncRupPenyedia([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi RUP Penyedia sedang dilakukan');
        return $this->redirect(['/rup/default']);
    }
    public function actionRupPenyediaAnggaran($url = null)
    {
        if (!$url) {
            // $url = 'https://isb.lkpp.go.id/isb/api/dda186fb-e227-42d0-8d61-745c128eb477/json/163364879/PaketAnggaranPenyedia1618/tipe/4:12/parameter/';
            $url = 'https://isb.lkpp.go.id/isb-2/api/c3480597-421b-4831-a5d5-13b6e0681896/json/15091/RUP-PaketAnggaranPenyedia/tipe/4:12/parameter/';
        }
        Yii::$app->queue->push(new QueueSyncBudgetRupPenyedia([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi Anggaran RUP Penyedia sedang dilakukan');
        return $this->redirect(['index']);
    }

    public function actionRupSwakelola($url = null)
    {
        if (!$url) {
            // $url = 'https://isb.lkpp.go.id/isb/api/5d3229d8-6b95-46e5-90e5-eeae759e0f44/json/163364659/PaketSwakelolaOpt1618/tipe/4:12/parameter/';
            $url = 'https://isb.lkpp.go.id/isb-2/api/1d1da328-b66a-4d74-9103-eb9880b32e84/json/15106/RUP-PaketSwakelola-Terumumkan/tipe/4:12/parameter/';
        }
        Yii::$app->queue->push(new QueueSyncRupSwakelola([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi RUP Swakelola sedang dilakukan');
        return $this->redirect(['index']);
    }

    public function actionRupSwakelolaAnggaran($url = null)
    {
        if (!$url) {
            // $url = 'https://isb.lkpp.go.id/isb/api/2b5fc7fd-10ae-455b-b528-789ac6ea223c/json/163364963/PaketAnggaranSwakelola1618/tipe/4:12/parameter/';
            $url = 'https://isb.lkpp.go.id/isb-2/api/8c86d679-b5e6-4781-926a-cf044461505b/json/15092/RUP-PaketAnggaranSwakelola/tipe/4:12/parameter/';
        }
        Yii::$app->queue->push(new QueueSyncBudgetRupSwakelola([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi Anggaran RUP Swakelola sedang dilakukan');
        return $this->redirect(['index']);
    }

    public function actionTenderPbj($url = null)
    {
        if (!$url) {
            $url = 'https://eprocinfo.bpbj.surakarta.go.id/api/rest-tender?filter[tahun]=';
        }
        Yii::$app->queue->push(new QueueSyncTenderPBJ([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi Tender PBJ');
        return $this->redirect(['/rup/pbj']);
    }

    public function actionNonTenderPbj($url = null)
    {
        if (!$url) {
            $url = 'https://eprocinfo.bpbj.surakarta.go.id/api/rest-non-tender?filter[tahun_anggaran]=';
        }
        Yii::$app->queue->push(new QueueSyncNonTenderPBJ([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi Non Tender PBJ');
        return $this->redirect(['/rup/pbj']);
    }

    public function actionRupProgram($url = null)
    {
        if (!$url) {
            $url = 'https://isb.lkpp.go.id/isb-2/api/525f2c7c-729a-4335-bb81-3c48acc119bb/json/15129/RUP-ProgramMaster/tipe/4:12/parameter/';
        }
        Yii::$app->queue->push(new QueueSyncRupProgram([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi RUP Program sedang dilakukan');
        return $this->redirect(['index']);
    }

    public function actionRupKegiatan($url = null)
    {
        if (!$url) {
            $url = 'https://isb.lkpp.go.id/isb-2/api/3742ea4f-1de4-4183-be70-8e632e8ddbcb/json/15089/RUP-KegiatanMaster/tipe/4:12/parameter/';
        }
        Yii::$app->queue->push(new QueueSyncRupKegiatan([
            'chunks' => [],
            'year' => $this->year,
            'url' => $url,
            'klpd_code' => $this->kode_kota,
        ]));
        Yii::$app->session->setFlash('warning', 'Sinkroinisasi RUP Kegiatan sedang dilakukan');
        return $this->redirect(['index']);
    }

    /**
     * Displays a single SyncLog model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionView($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //         'url_prev'  => Url::previous()
    //     ]);
    // }

    /**
     * Creates a new SyncLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new SyncLog();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
    //         return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
    //     }

    //     $this->populate_dropdown();

    //     return $this->render('create', [
    //         'model'         => $model,
    //         'dropdown_data' => $this->dropdown_data,
    //         'url_prev'      => Url::previous()
    //     ]);
    // }

    /**
     * Updates an existing SyncLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
    //         return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
    //     }

    //     $this->populate_dropdown();

    //     return $this->render('update', [
    //         'model'         => $model,
    //         'dropdown_data' => $this->dropdown_data,
    //         'url_prev'      => Url::previous()
    //     ]);
    // }

    /**
     * Deletes an existing SyncLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     $model = $this->findModel($id);
    //        if($model->delete()){
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
    //     } else{
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
    //     }

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the SyncLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SyncLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SyncLog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $sess = Yii::$app->session;
        $sess->remove('sync_log');
        return $this->redirect(['index']);
    }
}
