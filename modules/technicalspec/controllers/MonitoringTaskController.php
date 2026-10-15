<?php

namespace app\modules\technicalspec\controllers;

use app\models\Satker;
use app\models\Teamlist;
use app\modules\monitoring\models\MonitoringProgressSearch;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringTaskSearch;
use app\modules\monitoring\models\MonitoringTaskLocationsSearch;
use app\modules\monitoring\models\MonitoringProgressPicturesSearch;
use app\modules\references\models\ObjectType;
use app\modules\references\models\RefCriticalCondition;
use app\modules\references\models\RefKecamatan;
use app\modules\rup\models\Pbj;
use app\modules\rup\models\Rup;
use app\modules\technicalspec\models\DedDataSearch;
use app\modules\technicalspec\models\McoCco;
use app\modules\technicalspec\models\McoCcoSearch;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Request;

/**
 * MonitoringTaskController implements the CRUD actions for MonitoringTask model.
 */
class MonitoringTaskController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Team'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['map'],
                        'roles' => ['?', '@'],
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
        if(isset(Yii::$app->session['satker']['satker_id'])){
            $unit = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id'], 'id' => Yii::$app->session['satker']['satker_id']])->all();
        }
        else{
            $unit = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id']])->all();
        }


        if(isset(Yii::$app->session['team']['team_id'])){
            $teams = Teamlist::find()->where(['id' => Yii::$app->session['team']['team_id']])->all();
        }
        else{
            $teams = Teamlist::find()->all();
        }
        
        $this->dropdown_data['unit_id'] = ArrayHelper::map($unit, 'id', 'namecode');
        $this->dropdown_data['teams'] = ArrayHelper::map($teams, 'id', 'name');
        $objecttype = ObjectType::find()->orderBy(['name' => SORT_ASC])->all();
        $this->dropdown_data['object_type'] = ArrayHelper::map($objecttype, 'id', 'name');
        $kecamatans = RefKecamatan::find()->orderBy(['code' => SORT_ASC])->all();
        $this->dropdown_data['kecamatan_id'] = ArrayHelper::map($kecamatans, 'id', 'namecode');
        $criticalcondition = RefCriticalCondition::find()->orderBy(['code' => SORT_ASC])->all();
        $this->dropdown_data['criticalcondition'] = ArrayHelper::map($criticalcondition, 'id', 'name');
        // $this->dropdown_data['task_active'] = MonitoringTask::taskActiveStatusName();
        // $this->dropdown_data['bast_done'] = MonitoringTask::bastDoneStatusName();


    }

    /**
     * Lists all MonitoringTask models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_task'];
            if (isset($this->sess['monitoring_task']['page']))
                $_GET['page'] = $this->sess['monitoring_task']['page'];
        } else {
            $this->sess['monitoring_task'] = $params;
        }

        $searchModel = new MonitoringTaskSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MonitoringTask model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;

        // $planModel = new MonitoringPlanSearch();
        // $planDataProvider = $planModel->search($params,$id);
        // $implementationModel = new MonitoringImplementationSearch();
        // $implementationDataProvider = $implementationModel->search($params,$id);
        $ccoModel = new McoCcoSearch();
        $ccoDataProvider = $ccoModel->search($params, $id);
        $dedModel = new DedDataSearch();
        $dedDataProvider = $dedModel->search($params, $id);
        $progressModel = new MonitoringProgressSearch;
        $progressDataProvider = $progressModel->search($params, $id);
        $ccoDatas = McoCco::find()->select(['cco','name'])->where(['monitoring_task_id' => $id])->distinct()->all();
        $ccoDropDown = ArrayHelper::map($ccoDatas,'cco','name');

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'ccoModel' => $ccoModel,
            'ccoDataProvider' => $ccoDataProvider,
            'dedModel' => $dedModel,
            'dedDataProvider' => $dedDataProvider,
            'ccoDropDown' => $ccoDropDown,
            'progressModel' => $progressModel,
            'progressDataProvider' => $progressDataProvider,
        ]);
    }

    public function actionMap($id)
    {
        return $this->redirect(['/front-map/default/view','id' => $id]);

        $this->layout = '@app/themes/standard_front/views/layouts/main.php';
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;

        $locationsModel = new MonitoringTaskLocationsSearch();
        $locationsDataProvider = $locationsModel->search($params, $id);

        return $this->render('justmap', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'locationsModel' => $locationsModel,
            'locationsDataProvider' => $locationsDataProvider,
        ]);
    }

    public function actionGenerateBast()
    {
        $id = Yii::$app->request->post('id', null);
        $id = Yii::$app->hasher->decode($id);
        $date = Yii::$app->request->post('bastdate', null);

        if (!$id) {
            return $this->redirect(['index']);
        }

        $indodatetime = null;
        if (isset(Yii::$app->indodatetime)) {
            $indodatetime = Yii::$app->indodatetime;
        }

        if (!$date) {
            $date = date('Y-m-d');
        }
        $datearr = [];
        if ($date) {
            $dateintime = strtotime($date);
            $datearr['day'] = date('j', $dateintime);
            $datearr['dayname'] = date('l', $dateintime);
            $datearr['month'] = date('F', $dateintime);
            $datearr['year'] = date('Y', $dateintime);
            if ($indodatetime) {
                $datearr['dayname'] = $indodatetime->getHari(date('l', $dateintime));
                $datearr['month'] = $indodatetime->getBulan(date('n', $dateintime));
            }
        } else {
            $datearr['day'] = date('j');
            $datearr['dayname'] = date('l');
            $datearr['month'] = date('F');
            $datearr['year'] = date('Y');
            if ($indodatetime) {
                $datearr['dayname'] = $indodatetime->getHari(date('l'));
                $datearr['month'] = $indodatetime->getBulan(date('n'));
            }
        }

        // return $this->renderPartial('report_beritaacara', [
        //     'task' => $this->findModel($id),
        //     'date' => $datearr,
        // ]);

        $content = $this->renderPartial('report_beritaacara', [
            'task' => $this->findModel($id),
            'date' => $datearr,
            'renderpdf' => 1,
        ]);

        $content = preg_replace('/\>\s+\</m', '><', $content);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // Default Font
            'defaultFont' => 'Times',
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '',
            // any css to be embedded if required
            'cssInline' => '',
            // set mPDF properties on the fly
            'options' => ['title' => 'Berita Acara Monitoring Lapangan'],
            // call mPDF methods on the fly
            'methods' => [
                // 'SetHeader'=>['Rekap PDN'], 
                // 'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionReportMonitoring()
    {
        $id = Yii::$app->request->post('id', null);
        $id = Yii::$app->hasher->decode($id);
        $month = Yii::$app->request->post('month', null);
        $task = $this->findModel($id);

        if (!$id) {
            return $this->redirect(['index']);
        }

        //jenis 1 = tanpa laporan bulanan
        //jenis 2 = laporan satu bulan
        //jenis 3 = laporan semua bulan

        if ($month == 0) {
            $jenis = 1;
        } else if (($month < 0) || ($month > 12)) {
            $jenis = 3;
        } else {
            $jenis = 2;
        }

        // $qrcode = Builder::create()
        //     ->writer(new PngWriter())
        //     ->writerOptions([])
        //     ->data(Url::to(['/front-map/default/view', 'id' => Yii::$app->hasher->encode($id)], true))
        //     ->encoding(new Encoding('UTF-8'))
        //     ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        //     ->size(220)
        //     ->margin(0)
        //     ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        //     ->logoPath(Yii::getAlias('@app/web/images/logomurung.png'))
        //     ->logoResizeToWidth(45)
        //     ->logoPunchoutBackground(true)
        //     ->labelText(false)
        //     // ->labelFont(new NotoSans(20))
        //     ->labelAlignment(LabelAlignment::Center)
        //     ->validateResult(false)
        //     // ->backgroundColor(new Color(188, 194, 199))
        //     ->build();

        $gmap = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data("https://www.google.com/maps/search/?api=1&query=".$task->latlng)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(220)
            ->margin(0)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->logoPath(Yii::getAlias('@app/web/images/logomurung.png'))
            ->logoResizeToWidth(45)
            ->logoPunchoutBackground(true)
            ->labelText(false)
            // ->labelFont(new NotoSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->validateResult(false)
            // ->backgroundColor(new Color(188, 194, 199))
            ->build();

        // $qrcodestring = $qrcode->getDataUri();
        $gmapstring = $gmap->getDataUri();

        $content = $this->renderPartial('report_monitoring', [
            'task' => $task,
            'jenis' => $jenis,
            'month' => $month,
            // 'qrcode' => $qrcodestring,
            'gmap' => $gmapstring,
        ]);

        $content = preg_replace('/\>\s+\</m', '><', $content);
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        return $content;

        $content = $this->renderPartial('report_monitoring', [
            'task' => $task,
            'jenis' => $jenis,
            'month' => $month,
            // 'qrcode' => $qrcodestring,
            'renderpdf' => 1,
        ]);

        $content = preg_replace('/\>\s+\</m', '><', $content);
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // Default Font
            'defaultFont' => 'Times',
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '',
            // any css to be embedded if required
            'cssInline' => '',
            // set mPDF properties on the fly
            'options' => ['title' => 'Berita Acara Monitoring Lapangan'],
            // call mPDF methods on the fly
            'methods' => [
                // 'SetHeader'=>['Rekap PDN'], 
                // 'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Creates a new MonitoringTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MonitoringTask();

        $autofill = Yii::$app->request->post('autofill','0');
        if(($autofill == 1) || ($autofill == '1')){
            // var_dump(Yii::$app->request->post());
            $model->load(Yii::$app->request->post());
            $model = $this->autoFillRupPbj($model);
        }

        // $model = new MonitoringTask();
        if (($autofill == '0') && $model->load(Yii::$app->request->post())) {
            // $model = new MonitoringTask();
            $model->fiscal_year = $this->year;
            if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
            // $model->validate();
            // var_dump($model->errors);exit;
        }

        // $model->validate();
        // var_dump($model->errors);exit;

        $this->populate_dropdown();

        //default values?
        if ($model->estimated_week_finished == '') {
            $model->estimated_week_finished = 1;
        }
        if ($model->is_bpjstk == '') {
            $model->is_bpjstk = 0;
        }
        if ($model->implementation_status == '') {
            $model->implementation_status = 0;
        }
        if ($model->is_active == '') {
            $model->is_active = 1;
        }
        if ($model->is_bast_done == '') {
            $model->is_bast_done = 0;
        }

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    public function autoFillRupPbj($model)
    {
        if ($model instanceof MonitoringTask) {
            if ($model->idrup != '') {
                $rup = Rup::findOne(['idrup' => $model->idrup]);
                if ($rup) {
                    $model->name = $rup->namapaket;
                    $model->budget = $rup->jumlahpagu;
                    $model->unit_id = $rup->satker->id;
                    $model->start_date = $rup->tanggalawalpekerjaan;
                    $model->end_date = $rup->tanggalakhirpekerjaan;
                    $model->end_date = $rup->tanggalakhirpekerjaan;
                    $model->provider_selection_start_date = $rup->tanggalawalpemilihan;
                    $model->provider_selection_end_date = $rup->tanggalakhirpemilihan;
                    $model->fiscal_year = $rup->tahunanggaran;
                    $model->ppkom_name = $rup->ppk;
                    $model->ppkom_nip = $rup->nip_ppk;
                }
            }
            if ($model->pbj_id != '') {
                $pbj = Pbj::findOne(['kodelelang' => $model->pbj_id]);
                if ($pbj) {
                    $model->hps = $pbj->hps;
                    $model->contractor_name = $pbj->vendor_name;
                    $model->contract_number = $pbj->contract_no;
                    $model->work_days = $pbj->contract_duration;
                    $model->final_price = $pbj->contract_price;
                    $model->program_name = $pbj->program;
                    $model->kegiatan_name = $pbj->activity;
                    $model->hps = $pbj->hps;
                    $model->contract_date = Yii::$app->formatter->asDate($pbj->contract_date,'php:Y-m-d');
                }
            }
        }
        return $model;
    }

    /**
     * Updates an existing MonitoringTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        $autofill = Yii::$app->request->post('autofill','0');
        if(($autofill == 1) || ($autofill == '1')){
            // var_dump(Yii::$app->request->post());
            $model->load(Yii::$app->request->post());
            $model = $this->autoFillRupPbj($model);
        }

        if (($autofill == '0') && $model->load(Yii::$app->request->post()) && $model->save()) {
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
     * Deletes an existing MonitoringTask model.
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
     * Finds the MonitoringTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MonitoringTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_task');
        return $this->redirect(['index']);
    }
}
