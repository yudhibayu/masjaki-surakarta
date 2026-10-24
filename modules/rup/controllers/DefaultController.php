<?php

namespace app\modules\rup\controllers;

use app\models\Satker;
use Yii;

use app\modules\rup\models\Rup;
use app\modules\rup\models\Pbj;
use app\modules\rup\models\Rupjenispengadaan;
use app\modules\rup\models\Rupmetodepengadaan;
use app\modules\rup\models\RupSearch;
use app\queues\QueueSyncBudgetRupPenyedia;
use app\queues\QueueSyncBudgetRupSwakelola;
use app\queues\QueueSyncRupPenyedia;
use app\queues\QueueSyncRupSwakelola;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * DefaultController implements the CRUD actions for Rup model.
 */
class DefaultController extends Controller
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
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['Badan Usaha'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['depdrop-by-satker',],
                        'roles' => ['@'],
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
        $sessionYear = $this->sess['tahun'] ?? $this->sess['year'] ?? $this->sess['fiscal_year'] ?? (int)date('Y');
        if (is_array($sessionYear)) {
            $sessionYear = $sessionYear['name'] ?? null;
        }
        $this->year = is_scalar($sessionYear) && $sessionYear !== ''
            ? $sessionYear
            : (int)date('Y');
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

        // $this->dropdown_data['metodepengadaan'] = Rup::SELECT_METODE_PENGADAAN;
        // $this->dropdown_data['jenispengadaan'] = Rup::SELECT_JENIS_PENGADAAN;
        $this->dropdown_data['metodepengadaan'] = ArrayHelper::map(Rupmetodepengadaan::find()->all(), 'id', 'name');
        $this->dropdown_data['jenispengadaan'] = ArrayHelper::map(Rupjenispengadaan::find()->all(), 'id', 'name');
        $this->dropdown_data['statuspradipa'] = Rup::SELECT_PRADIPA;
        $this->dropdown_data['statuspenyedia'] = Rup::SELECT_PENYEDIA;
        $this->dropdown_data['statuspdn'] = Rup::SELECT_PDN;
        $this->dropdown_data['statususahakecil'] = Rup::SELECT_USAHA_KECIL;
        $this->dropdown_data['statusumumkan'] = Rup::SELECT_STATUS_UMUMKAN;
        $this->dropdown_data['statuspaketkonsolidasi'] = Rup::SELECT_PAKET_KONSOLIDASI;
    }

    /**
     * Lists all Rup models.
     * @return mixed
     */
    public function actionIndex()
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
        $dataProvider = $searchModel->search($params);

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

    /**
     * Displays a single Rup model.
     * @param integer $id
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
     * Creates a new Rup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rup();
        if ($this->year) {
            $model->tahunanggaran = (string)$this->year;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->jenispengadaan = 'Pekerjaan Konstruksi';
            $model->statusumumkan = 'Terumumkan';
            if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->idrup)]);
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
     * Updates an existing Rup model.
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
     * Deletes an existing Rup model.
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
        $parents = Yii::$app->request->post('depdrop_parents', []);
        if (!is_array($parents) || count($parents) < 2
            || !is_scalar($parents[0]) || !is_scalar($parents[1])
            || !ctype_digit((string)$parents[0]) || !ctype_digit((string)$parents[1])) {
            return ['output' => [], 'selected' => ''];
        }

        $satker = Satker::findOne(['id' => (int)$parents[0]]);
        $idJenisPengadaan = (int)$parents[1];
        if ($satker === null) {
            return ['output' => [], 'selected' => ''];
        }

        // Data RUP lama memakai kode Satker lokal, sedangkan hasil SIRUP baru
        // menyimpan ID Satker dan nama Satker SIRUP pada tabel PBJ.
        $options = [];
        $sortNames = [];
        $rupList = Rup::find()
            ->where(['kodesatker' => $satker->code])
            ->andWhere(['idjenispengadaan' => $idJenisPengadaan])
            ->andWhere(['tahunanggaran' => $this->year])
            ->orderBy(['namapaket' => SORT_ASC])
            ->all();
        foreach ($rupList as $rup) {
            $options[(string)$rup->idrup] = $rup->namecodeannounced;
            $sortNames[(string)$rup->idrup] = $rup->namapaket;
        }

        $sirupSatkerNames = $satker->getSirupNameVariants();
        $pbjList = Pbj::find()
            ->where(['fiscal_year' => $this->year])
            ->andWhere(['idjenispengadaan' => $idJenisPengadaan])
            ->andWhere([
                'or',
                ['kodesatker' => $satker->code],
                ['namasatker' => array_values(array_unique($sirupSatkerNames))],
            ])
            ->orderBy(['name' => SORT_ASC])
            ->all();
        foreach ($pbjList as $pbj) {
            $rupCode = $pbj->rup_code ?: $pbj->kodelelang;
            if ($rupCode && !isset($options[(string)$rupCode])) {
                $options[(string)$rupCode] = '(' . $rupCode . ') ' . $pbj->name;
                $sortNames[(string)$rupCode] = $pbj->name;
            }
        }

        uksort($options, static function ($left, $right) use ($sortNames) {
            $byName = strnatcasecmp($sortNames[$left] ?? (string)$left, $sortNames[$right] ?? (string)$right);
            return $byName !== 0 ? $byName : strnatcasecmp((string)$left, (string)$right);
        });
        $out = [];
        foreach ($options as $id => $name) {
            $out[] = ['id' => $id, 'name' => $name];
        }

        return ['output' => $out, 'selected' => ''];
    }

    public function actionSync()
    {
        $tahun = $this->year;
        $kode = '';
        if ($tahun == 0) {
            $tahun = (int)date('Y');
        }
        if ($kode == '') {
            $kode = 'D147';
        }
        $url = '';
        if ($url == '') {
            $url = 'https://isb.lkpp.go.id/isb-2/api/4eb7e158-259e-4a6a-9e20-fdf43ca9f678/json/15105/RUP-PaketPenyedia-Terumumkan/tipe/4:12/parameter/';
        }

        Yii::$app->queue->push(new QueueSyncRupPenyedia([
            'chunks' => [],
            'year' => $tahun,
            'url' => $url,
            'klpd_code' => $kode,
        ]));

        return $this->redirect(['index']);
    }

    public function actionSyncSwakelola()
    {
        $tahun = $this->year;
        $kode = '';
        if ($tahun == 0) {
            $tahun = (int)date('Y');
        }
        if ($kode == '') {
            $kode = 'D147';
        }
        $url = '';
        if ($url == '') {
            $url = 'https://isb.lkpp.go.id/isb-2/api/1d1da328-b66a-4d74-9103-eb9880b32e84/json/15106/RUP-PaketSwakelola-Terumumkan/tipe/4:12/parameter/';
        }

        Yii::$app->queue->push(new QueueSyncRupSwakelola([
            'chunks' => [],
            'year' => $tahun,
            'url' => $url,
            'klpd_code' => $kode,
        ]));

        return $this->redirect(['index']);
    }

    public function actionSyncBudget()
    {
        $tahun = $this->year;
        $kode = '';
        if ($tahun == 0) {
            $tahun = (int)date('Y');
        }
        if ($kode == '') {
            $kode = 'D147';
        }
        $url1 = '';
        $url2 = '';
        if ($url1 == '') {
            $url1 = 'https://isb.lkpp.go.id/isb-2/api/c3480597-421b-4831-a5d5-13b6e0681896/json/15091/RUP-PaketAnggaranPenyedia/tipe/4:12/parameter/';
        }
        if ($url2 == '') {
            $url2 = 'https://isb.lkpp.go.id/isb-2/api/8c86d679-b5e6-4781-926a-cf044461505b/json/15092/RUP-PaketAnggaranSwakelola/tipe/4:12/parameter/';
        }

        Yii::$app->queue->push(new QueueSyncBudgetRupPenyedia([
            'chunks' => [],
            'year' => $tahun,
            'url' => $url1,
            'klpd_code' => $kode,
        ]));

        Yii::$app->queue->push(new QueueSyncBudgetRupSwakelola([
            'chunks' => [],
            'year' => $tahun,
            'url' => $url2,
            'klpd_code' => $kode,
        ]));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('rup');
        return $this->redirect(['index']);
    }
}
