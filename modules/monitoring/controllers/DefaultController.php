<?php

namespace app\modules\monitoring\controllers;

use app\models\Eksekutorlist;
use app\models\Plannerlist;
use app\models\Satker;
use app\models\Supervisorlist;
use app\models\Teamlist;
use app\models\Usersatker;
use app\modules\monitoring\models\MonitoringEksekutor;
use app\modules\monitoring\models\MonitoringEksekutorSearch;
use app\modules\monitoring\models\MonitoringPlanner;
use app\modules\monitoring\models\MonitoringPlannerNoteSearch;
use app\modules\monitoring\models\MonitoringPlannerSearch;
use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringProgressSearch;
use app\modules\monitoring\models\MonitoringSupervisor;
use app\modules\monitoring\models\MonitoringSupervisorSearch;
use app\modules\monitoring\models\MonitoringTagging;
use app\modules\monitoring\models\MonitoringTaggingSearch;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringTaskFile;
use app\modules\monitoring\models\MonitoringTaskSearch;
use app\modules\monitoring\models\MonitoringTaskLocationsSearch;
use app\modules\monitoring\models\MonitoringProgressPicturesSearch;
use app\modules\references\models\ObjectType;
use app\modules\references\models\RefCriticalCondition;
use app\modules\references\models\RefKecamatan;
use app\modules\references\models\RefTagging;
use app\modules\rup\models\Pbj;
use app\modules\rup\models\Rup;
use app\modules\rup\models\Rupjenispengadaan;
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
use yii\base\Exception;
use app\overrides\base_model\Model;
use app\overrides\user\models\AuthAssignment;
use app\overrides\user\models\User;
use yii\web\UploadedFile;
use yii\base\UserException;
use Ramsey\Uuid\Uuid;

/**
 * DefaultController implements the CRUD actions for MonitoringTask model.
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
                    'set-tab' => ['POST'],
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
                        'actions' => ['index', 'view', 'reset_session', 'set-tab', 'report-monitoring'],
                        'roles' => ['Viewer', 'Eksekutif', 'Planner', 'Eksekutor', 'Supervisor',],
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
        $sessionYear = $this->sess['tahun'] ?? $this->sess['year'] ?? $this->sess['fiscal_year'] ?? (int)date('Y');
        if (is_array($sessionYear)) {
            $sessionYear = $sessionYear['name'] ?? null;
        }
        $this->year = is_scalar($sessionYear) && $sessionYear !== ''
            ? (int)$sessionYear
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
            $unit = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id'], 'id' => Yii::$app->session['satker']['satker_id']])->all();
        } else {
            $unit = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id']])->all();
        }


        $teams = $this->getSelectableTeams();

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
        $jenispengadaan = Rupjenispengadaan::find()->orderBy(['name' => SORT_ASC])->all();
        $this->dropdown_data['idjenispengadaanrup'] = ArrayHelper::map($jenispengadaan, 'id', 'name');
        $jenispengadaanpbj = Pbj::find()->select(['jenispengadaan'])->distinct()->all();
        $this->dropdown_data['idjenispengadaanpbj'] = ArrayHelper::map($jenispengadaanpbj, 'jenispengadaan', 'jenispengadaan');
    }

    private function getSelectableTeams()
    {
        $isTeamUser = Yii::$app->user->can('Team')
            && !Yii::$app->user->can('Administrator')
            && !Yii::$app->user->can('OPD');

        $teamSession = Yii::$app->session->get('team', []);
        $teamId = is_array($teamSession) ? ($teamSession['team_id'] ?? null) : null;

        if ($isTeamUser && $teamId) {
            $selectedTeam = Teamlist::find()->where(['id' => $teamId])->orderBy(['name' => SORT_ASC])->all();
            if ($selectedTeam) {
                return $selectedTeam;
            }
        }

        return Teamlist::find()->orderBy(['name' => SORT_ASC])->all();
    }

    public function actionTeamOptions()
    {
        $options = [];
        foreach ($this->getSelectableTeams() as $team) {
            $options[] = ['id' => (string)$team->id, 'text' => $team->name];
        }

        return $this->asJson($options);
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

        $model = $this->findModel($id);
        $weeks = $model->getOrderMonitoringAvaiable();
        $weeks_monitorings = [];
        $plans = [];
        $realisations = [];
        sort($weeks, SORT_NUMERIC);
        foreach ($weeks as $week) {
            $thisweek = $model->getLatestMonitoringByOrder($week);
            $weeks_monitorings[$week] = [
                'planned_physical_percent' => (float)$thisweek->planned_physical_percent,
                'physical_percent' => (float)$thisweek->physical_percent,
            ];
        }
        ksort($weeks_monitorings, SORT_NUMERIC);
        foreach ($weeks_monitorings as $weekm) {
            $plans[] = $weekm['planned_physical_percent'];
            $realisations[] = $weekm['physical_percent'];
        }

        if (!isset($this->sess['monitoring_view_tab']['nav'])) {
            $this->sess->set('monitoring_view_tab', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                    2 => '',
                    3 => '',
                    4 => '',
                    5 => '',
                    6 => '',
                    7 => '',
                ],
                'tab' => [
                    0 => 'active',
                    1 => 'fade',
                    2 => 'fade',
                    3 => 'fade',
                    4 => 'fade',
                    5 => 'fade',
                    6 => 'fade',
                    7 => 'fade',
                ],
            ]);
        }

        $params = Yii::$app->request->queryParams;

        $progressModel = new MonitoringProgressSearch;
        $progressDataProvider = $progressModel->search($params, $id);

        $locationsModel = new MonitoringTaskLocationsSearch();
        $locationsDataProvider = $locationsModel->search($params, $id);

        $picturesModel = new MonitoringProgressPicturesSearch();
        $picturesDataProvider = $picturesModel->search($params, null, null, $id);

        $ccoModel = new McoCcoSearch();
        $ccoDataProvider = $ccoModel->search($params, $id);

        $dedModel = new DedDataSearch();
        $dedDataProvider = $dedModel->search($params, $id);

        $ccoDatas = McoCco::find()->select(['cco', 'name'])->where(['monitoring_task_id' => $id])->distinct()->all();
        $ccoDropDown = ArrayHelper::map($ccoDatas, 'cco', 'name');

        $taggingModel = new MonitoringTaggingSearch();
        $taggingDataProvider = $taggingModel->search($params, $id);

        $plannerModel = new MonitoringPlannerSearch();
        $plannerDataProvider = $plannerModel->search($params, $id);

        $supervisorModel = new MonitoringSupervisorSearch();
        $supervisorDataProvider = $supervisorModel->search($params, $id);

        $eksekutorModel = new MonitoringEksekutorSearch();
        $eksekutorDataProvider = $eksekutorModel->search($params, $id);

        $ccoModel = new McoCcoSearch();
        $ccoDataProvider = $ccoModel->search($params, $id);

        $dedModel = new DedDataSearch();
        $dedDataProvider = $dedModel->search($params, $id);

        $ccoDatas = McoCco::find()->select(['cco', 'name'])->where(['monitoring_task_id' => $id])->distinct()->all();
        $ccoDropDown = ArrayHelper::map($ccoDatas, 'cco', 'name');

        $plannerNoteModel = new MonitoringPlannerNoteSearch();
        $plannerNoteDataProvider = $plannerNoteModel->search($params, $id);

        $modelfile = new MonitoringTaskFile();

        $monitoring_view_tab = $this->sess['monitoring_view_tab'];

        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),

            'progressModel' => $progressModel,
            'progressDataProvider' => $progressDataProvider,

            'locationsModel' => $locationsModel,
            'locationsDataProvider' => $locationsDataProvider,

            'picturesModel' => $picturesModel,
            'picturesDataProvider' => $picturesDataProvider,

            'taggingModel' => $taggingModel,
            'taggingDataProvider' => $taggingDataProvider,

            'plannerModel' => $plannerModel,
            'plannerDataProvider' => $plannerDataProvider,

            'supervisorModel' => $supervisorModel,
            'supervisorDataProvider' => $supervisorDataProvider,

            'eksekutorModel' => $eksekutorModel,
            'eksekutorDataProvider' => $eksekutorDataProvider,

            'ccoModel' => $ccoModel,
            'ccoDataProvider' => $ccoDataProvider,

            'dedModel' => $dedModel,
            'dedDataProvider' => $dedDataProvider,

            'progressModel' => $progressModel,
            'progressDataProvider' => $progressDataProvider,

            'plannerNoteModel' => $plannerNoteModel,
            'plannerNoteDataProvider' => $plannerNoteDataProvider,

            'ccoDropDown' => $ccoDropDown,
            'modelfile' => $modelfile,
            'weeks_monitorings' => $weeks_monitorings,
            'weeks' => $weeks,
            'plans' => $plans,
            'realisations' => $realisations,
            'monitoring_view_tab' => $monitoring_view_tab,
        ]);
    }

    public function actionSetTab()
    {
        if (!isset($this->sess['monitoring_view_tab']['nav'])) {
            $this->sess->set('monitoring_view_tab', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                    2 => '',
                    3 => '',
                    4 => '',
                    5 => '',
                    6 => '',
                    7 => '',
                ],
                'tab' => [
                    0 => 'active',
                    1 => 'fade',
                    2 => 'fade',
                    3 => 'fade',
                    4 => 'fade',
                    5 => 'fade',
                    6 => 'fade',
                    7 => 'fade',
                ],
            ]);
        }
        $navs = $this->sess['monitoring_view_tab']['nav'];
        $tabs = $this->sess['monitoring_view_tab']['tab'];
        $tabid = (int) Yii::$app->request->post('tab', 0);
        if ($tabid < 0 || $tabid > 7) {
            $tabid = 0;
        }
        for ($i = 0; $i < 8; $i++) {
            if ($i == $tabid) {
                $navs[$i] = 'active';
                $tabs[$i] = 'active';
            } else {
                $navs[$i] = '';
                $tabs[$i] = 'fade';
            }
        }
        $this->sess->set('monitoring_view_tab', [
            'nav' => $navs,
            'tab' => $tabs,
        ]);
        // $this->sess['monitoring_view_tab']['nav'] = $navs;
        // $this->sess['monitoring_view_tab']['tab'] = $tabs;

        return $this->asJson(['status' => 'ok']);
    }

    public function actionMap($id)
    {
        return $this->redirect(['/front-map/default/view', 'id' => $id]);

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
        // var_dump(Yii::$app->request->post());exit;
        $id = Yii::$app->request->post('id', null);
        $id = Yii::$app->hasher->decode($id);
        $type = Yii::$app->request->post('type', 'month');
        $month = Yii::$app->request->post('month', 0);
        $week = Yii::$app->request->post('week', null);
        $day = Yii::$app->request->post('day', null);
        $is_spec = Yii::$app->request->post('is_spec', false);
        $is_shared = Yii::$app->request->post('is_shared', false);

        if ($is_shared) {
            return SharedMonitoringReportController::actionCreate();
        }

        $task = $this->findModel($id);

        if (!$id) {
            return $this->redirect(['index']);
        }
        if ($is_spec && ($is_spec != 0)) {
            $is_spec = true;
        }

        //jenis 1 = tanpa laporan bulanan & mingguan
        //jenis 2 = laporan satu bulan
        //jenis 3 = laporan semua bulan
        //jenis 4 = laporan satu minggu
        //jenis 5 = laporan satu hari
        if (($type == 'day') && $day) {
            $jenis = 5;
        } else if (($type == 'week') && $week) {
            $jenis = 4;
        } else {
            if ($month == 0) {
                $jenis = 1;
            } else if (($month < 0) || ($month > 12)) {
                $jenis = 3;
            } else {
                $jenis = 2;
            }
        }

        $gmap = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data("https://www.google.com/maps/search/?api=1&query=" . $task->latlng)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(220)
            ->margin(0)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->logoPath(Yii::getAlias('@app/web/images/logo-ska.png'))
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
            'gmap' => $gmapstring,
            'month' => $month,
            'week' => $week,
            'day' => $day,
            'is_spec' => $is_spec,
        ]);

        $content = preg_replace('/\>\s+\</m', '><', $content);
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        return $content;
    }

    /**
     * Creates a new MonitoringTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MonitoringTask();

        $autofill = Yii::$app->request->post('autofill', '0');
        if (($autofill == 1) || ($autofill == '1')) {
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
                } else {
                    $sirupPbj = Pbj::find()
                        ->where(['fiscal_year' => $this->year])
                        ->andWhere([
                            'or',
                            ['rup_code' => $model->idrup],
                            ['kodelelang' => $model->idrup],
                        ])
                        ->one();
                    if ($sirupPbj) {
                        $model->name = $sirupPbj->name;
                        $model->budget = $sirupPbj->budget;
                        $model->fiscal_year = $sirupPbj->fiscal_year;
                    }
                }
            }
            if ($model->pbj_id != '') {
                $pbj = Pbj::findOne(['kodelelang' => $model->pbj_id]);
                if ($pbj) {
                    $model->hps = $pbj->hps;
                    $model->bid_price = $pbj->bid_price;
                    $model->contractor_name = $pbj->vendor_name;
                    $model->contract_number = $pbj->contract_no;
                    $model->work_days = $pbj->contract_duration;
                    $model->final_price = $pbj->contract_price;
                    $model->final_price = $pbj->final_price;
                    $model->program_name = $pbj->program;
                    $model->kegiatan_name = $pbj->activity;
                    $model->hps = $pbj->hps;
                    $model->contract_date = $pbj->contract_date ? Yii::$app->formatter->asDate($pbj->contract_date, 'php:Y-m-d') : null;
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

        $autofill = Yii::$app->request->post('autofill', '0');
        if (($autofill == 1) || ($autofill == '1')) {
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
     * Addes an existing Plannerlist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddPlanner($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model                = $this->findModel($id);
        $model_planner        = new MonitoringPlanner();
        $model_planner->monitoring_task_id = $model->id;

        if ($model_planner->load(Yii::$app->request->post()) && $model_planner->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        // tambah ke daftar planner
        $plannerid = MonitoringPlanner::find()->select('user_id')->where(['monitoring_task_id' => $model->id])->column();
        if (!empty($plannerid)) {
            $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Planner'])->andWhere(['not in', 'user_id', $plannerid])->column();
        } else {
            $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Planner'])->column();
        }
        $planners = User::find()->where(['in', 'id', $assignments])->all();

        $this->dropdown_data['planner_id'] = [];
        foreach ($planners as $planner) {
            $name = isset($planner->profile->name) ? $planner->profile->name : $planner->username;
            $this->dropdown_data['planner_id'][$planner->id] = $name;
        }

        return $this->render('add-planner', [
            'model'                   => $model,
            'model_planner'           => $model_planner,
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous()
        ]);
    }

    public function actionEditPlanner($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model_planner        = MonitoringPlanner::findOne($id);
        $model                = $this->findModel($model_planner->monitoring_task_id);

        if ($model_planner->load(Yii::$app->request->post()) && $model_planner->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Planner'])->column();

        $planners = User::find()->where(['in', 'id', $assignments])->all();

        $this->dropdown_data['planner_id'] = [];
        foreach ($planners as $planner) {
            $name = isset($planner->profile->name) ? $planner->profile->name : $planner->username;
            $this->dropdown_data['planner_id'][$planner->id] = $name;
        }

        return $this->render('add-planner', [
            'model'                   => $model,
            'model_planner'           => $model_planner,
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous(),
            'is_edit'                 => true,
        ]);
    }

    /**
     * Deletes an existing MonitoringPlanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletePlanner($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = MonitoringPlanner::findOne($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->planner_name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->planner_name]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
    }


    /**
     * Addes an existing Supervisorlist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddSupervisor($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model                = $this->findModel($id);
        $model_supervisor     = new MonitoringSupervisor();
        $model_supervisor->monitoring_task_id = $model->id;

        if ($model_supervisor->load(Yii::$app->request->post()) && $model_supervisor->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $supervisorid = MonitoringSupervisor::find()->select('supervisor_id')->where(['monitoring_task_id' => $model->id])->column();
        if (!empty($supervisorid)) {
            $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Supervisor'])->andWhere(['not in', 'user_id', $supervisorid])->column();
        } else {
            $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Supervisor'])->column();
        }
        $supervisors = User::find()->where(['in', 'id', $assignments])->all();

        $this->dropdown_data['supervisor_id'] = [];
        foreach ($supervisors as $supervisor) {
            $name = isset($supervisor->profile->name) ? $supervisor->profile->name : $supervisor->username;
            $this->dropdown_data['supervisor_id'][$supervisor->id] = $name;
        }


        return $this->render('add-supervisor', [
            'model'                   => $model,
            'model_supervisor'        => new MonitoringSupervisor(),
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous()
        ]);
    }

    public function actionEditSupervisor($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model_supervisor     = MonitoringSupervisor::findOne($id);
        $model                = $this->findModel($model_supervisor->monitoring_task_id);

        if ($model_supervisor->load(Yii::$app->request->post()) && $model_supervisor->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Supervisor'])->column();
        $supervisors = User::find()->where(['in', 'id', $assignments])->all();

        $this->dropdown_data['supervisor_id'] = [];
        foreach ($supervisors as $supervisor) {
            $name = isset($supervisor->profile->name) ? $supervisor->profile->name : $supervisor->username;
            $this->dropdown_data['supervisor_id'][$supervisor->id] = $name;
        }

        return $this->render('add-supervisor', [
            'model'                   => $model,
            'model_supervisor'        => $model_supervisor,
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous(),
            'is_edit'                 => true,
        ]);
    }

    /**
     * Deletes an existing MonitoringSupervisor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteSupervisor($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = MonitoringSupervisor::findOne($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->supervisor_name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->supervisor_name]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
    }

    /**
     * Addes an existing Eksekutorlist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddEksekutor($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model                = $this->findModel($id);
        $model_eksekutor      = new MonitoringEksekutor();
        $model_eksekutor->monitoring_task_id = $model->id;

        if ($model_eksekutor->load(Yii::$app->request->post()) && $model_eksekutor->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $eksekutorid = MonitoringEksekutor::find()->select('eksekutor_id')->where(['monitoring_task_id' => $model->id])->column();
        if (!empty($eksekutorid)) {
            $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Eksekutor'])->andWhere(['not in', 'user_id', $eksekutorid])->column();
        } else {
            $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Eksekutor'])->column();
        }
        $eksekutors = User::find()->where(['in', 'id', $assignments])->all();

        $this->dropdown_data['eksekutor_id'] = [];
        foreach ($eksekutors as $eksekutor) {
            $name = isset($eksekutor->profile->name) ? $eksekutor->profile->name : $eksekutor->username;
            $this->dropdown_data['eksekutor_id'][$eksekutor->id] = $name;
        }

        return $this->render('add-eksekutor', [
            'model'                   => $model,
            'model_eksekutor'         => (empty($model_eksekutor)) ? [new MonitoringEksekutor()] : $model_eksekutor,
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous()
        ]);
    }

    public function actionEditEksekutor($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model_eksekutor      = MonitoringEksekutor::findOne(['id' => $id]);
        $model                = $this->findModel($model_eksekutor->monitoring_task_id);

        if ($model_eksekutor->load(Yii::$app->request->post()) && $model_eksekutor->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $assignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Eksekutor'])->column();
        $eksekutors = User::find()->where(['in', 'id', $assignments])->all();

        $this->dropdown_data['eksekutor_id'] = [];
        foreach ($eksekutors as $eksekutor) {
            $name = isset($eksekutor->profile->name) ? $eksekutor->profile->name : $eksekutor->username;
            $this->dropdown_data['eksekutor_id'][$eksekutor->id] = $name;
        }

        return $this->render('add-eksekutor', [
            'model'                   => $model,
            'model_eksekutor'         => $model_eksekutor,
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous(),
            'is_edit'                 => true,
        ]);
    }

    /**
     * Deletes an existing MonitoringEksekutor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteEksekutor($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = MonitoringEksekutor::findOne($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->eksekutor_name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->eksekutor_name]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
    }

    /**
     * Addes an existing Tagginglist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddTagging($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model               = $this->findModel($id);
        $model_tagging       = new MonitoringTagging();
        $model_tagging->monitoring_task_id = $model->id;

        if ($model_tagging->load(Yii::$app->request->post()) && $model_tagging->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        // tambah ke daftar tagging
        $taggingid = MonitoringTagging::find()->select('tagging_id')->where(['monitoring_task_id' => $model->id])->column();
        if (!empty($taggingid)) {
            $taggings = RefTagging::find()->where(['not in', 'id', $taggingid])->all();
        } else {
            $taggings = RefTagging::find()->all();
        }

        $this->dropdown_data['tagging'] = ArrayHelper::map($taggings, 'id', 'name');

        return $this->render('add-tagging', [
            'model'                   => $model,
            'model_tagging'           => $model_tagging,
            'dropdown_data'           => $this->dropdown_data,
            'url_prev'                => Url::previous()
        ]);
    }


    /**
     * Deletes an existing tagging model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteTagging($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = MonitoringTagging::findOne(['id' => $id]);
        $task_id = $model->monitoring_task_id;
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Record can not be deleted.'));
        }

        return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($task_id)]);
    }

    /**
     * Add an existing MonitoringTaskFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new MonitoringTaskFile();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_name');
            if ($file) {
                $uuid = Uuid::uuid6();
                $newname = $uuid->toString() . '.' . $file->extension;
                $model->file_name = $newname;
                $file->saveAs($model->filePath() . $newname);
                $model->monitoring_task_id = $id;
                if (!$model->save()) {
                    $model->validate();
                    throw new UserException(json_encode($model->errors));
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been created'));
                return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            } else {
                $model->monitoring_task_id = $id;
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been created but your file is not uploaded'));
                return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            }
        }
    }

    /**
     * Updates an existing MonitoringTaskFile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = MonitoringTaskFile::findOne($id);
        $olddraft = $model->file_name;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_name');
            if (!empty($file)) {
                $uuid = Uuid::uuid6();
                $newname = $uuid->toString() . '.' . $file->extension;
                $model->monitoring_task_id = $model->monitoring_task_id;
                $model->file_name = $newname;
                $file->saveAs($model->filePath() . $newname);
                if (!$model->save()) {
                    $model->validate();
                    throw new UserException(json_encode($model->errors));
                }
                if ($olddraft != NULL) {
                    if (file_exists(Yii::getAlias('@upload') . '/'  . $olddraft)) {
                        unlink(Yii::getAlias('@upload') . '/' . $olddraft);
                    }
                }
            } else {
                $model->file_name = $olddraft;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been updated.'));
                return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            }
        }

        $this->populate_dropdown();

        return $this->render('update-file', [
            'model'         => $model,
        ]);
    }

    /**
     * Deletes an existing MonitoringTaskFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteFile($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = MonitoringTaskFile::findOne($id);
        if ($model->delete()) {
            if ($model->removeFile()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record successfully deleted.'));
            } else {
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Record can not be deleted.'));
        }

        return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
    }

    /**
     * show file upload from MonitoringTaskFile model.
     * If show is successful, the browser will be show file upload.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFileItem($id = null)
    {
        $path = MonitoringTaskFile::filePath();
        $this->showfile($path, $id);
    }

    private function showfile($path, $id = null)
    {
        if (!$id) {
            throw new NotFoundHttpException('file not found');
        } else {
            $response = Yii::$app->response;
            $filepath = $path . $id;
            if (!is_file($filepath) || !preg_match('/^[a-zA-Z0-9_.-]*$/i', $id)) {
                throw new NotFoundHttpException('file not found');
            } else {
                $response->sendFile($filepath, null, ['inline' => true]);
            }
        }
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
