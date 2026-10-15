<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\base\Theme;
use yii\helpers\Url;
use yii\web\Session;
use yii\web\Response;
use app\models\Satker;
use yii\db\Expression;
use yii\web\Controller;
use app\models\Teamlist;
use app\models\Teamuser;
use app\models\UserSatker;
use yii\base\DynamicModel;
use app\components\Theming;
use app\models\ContactForm;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\rup\models\Pbj;
use app\modules\rup\models\Rup;
use yii\data\ActiveDataProvider;
use app\modules\rup\models\PbjSearch;
use app\modules\rup\models\RupSearch;
use app\modules\hitcounter\models\HitCounter;
use app\modules\references\models\Year_model;
use app\modules\construction\models\WorkTraining;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringProgress;
use app\modules\construction\models\ConstructionSafety;
use app\modules\construction\models\WorkTrainingSearch;
use app\modules\monitoring\models\MonitoringTaskSearch;
use app\modules\construction\models\ConstructionAccident;
use app\modules\technicalspec\models\MonitoringSpecFiles;
use app\modules\construction\models\ConstructionVacancies;
use app\modules\construction\models\ConstructionSafetySearch;
use app\modules\construction\models\ConstructionAccidentSearch;
use app\modules\construction\models\ConstructionVacanciesSearch;
use app\overrides\user\models\User;

class AdminController extends Controller
{
    private $sess;
    private $year;
    private $unit_id = null;
    private $team_id = null;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
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
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        //monggo
        $this->sess = Yii::$app->session;
        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $this->year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
        } else {
            $this->year = date('Y');
        }

        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $this->unit_id = Yii::$app->session['satker']['satker_id'];
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $this->team_id = Yii::$app->session['team']['team_id'];
        }
        /*         $this->user = Yii::$app->user->identity;
        $this->req = Yii::$app->request->post(); */
        $user = User::findOne(['id' => Yii::$app->user->id]);
        if ($user) {
            if ($user->pwd_expire < time()) {
                Yii::$app->session->setFlash('warning', 'Kata sandi sudah kadaluarsa. Harap mengganti kata sandi sebelum menggunakan aplikasi ini.', false);
                return $this->redirect(['/auth/settings/accountadmin']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        Theming::setThemes(Yii::$app->params['backEndTheme']);

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
        $session = Yii::$app->session;
        $params = Yii::$app->request->queryParams;

        // if (!Yii::$app->user->can('Team') && !$session->has('unit')){
        //     return $this->redirect('/manage_unit/user-unit/unit');
        // } 
        if (!$session->has('milestone')) {
            return $this->redirect('/admin/year');
        }

        $paket_tepatwaktu = [];
        $paket_terlambat = [];
        $paket_selesai = [];
        $paket_dalamproses = [];
        $getmonitoringtaskid = MonitoringProgress::find()->select('monitoring_task_id')->where(['date_year' => $this->year])->column();
        $monitoring_task = MonitoringTask::find()->where(['fiscal_year' => $this->year])->andWhere(['in', 'id', $getmonitoringtaskid])->orderBy('id ASC')->all();
        foreach ($monitoring_task as $mt) {
            if (($mt['latest_plan_physical_percent'] != 100) && ($mt['latest_monitored_physical_percent'] != 100) && ($mt['latest_plan_physical_percent'] <= $mt['latest_monitored_physical_percent'])) {
                array_push($paket_tepatwaktu, $mt['id']);
                array_push($paket_dalamproses, $mt['id']);
            } else if ($mt['latest_plan_physical_percent'] > $mt['latest_monitored_physical_percent']) {
                array_push($paket_terlambat, $mt['id']);
                array_push($paket_dalamproses, $mt['id']);
            } else if (($mt['latest_plan_physical_percent'] == 100) && ($mt['latest_monitored_physical_percent'] == 100)) {
                array_push($paket_selesai, $mt['id']);
            }
        }

        $totaltw = count($paket_tepatwaktu);
        $totaltr = count($paket_terlambat);
        $totals = count($paket_selesai);
        $totaldp = count($paket_dalamproses);

        $searchModelPaket = new MonitoringTaskSearch(["formNameParam" => "grid1"]);
        $searchModelPaket2 = new MonitoringTaskSearch(["formNameParam" => "grid2"]);
        $searchModelPaket3 = new MonitoringTaskSearch(["formNameParam" => "grid3"]);
        $searchModelPaket4 = new MonitoringTaskSearch(["formNameParam" => "grid4"]);
        $dataProviderPaket = $searchModelPaket->searchPaket($params, $paket_tepatwaktu);
        $dataProviderPaket2 = $searchModelPaket2->searchPaketTr($params, $paket_terlambat);
        $dataProviderPaket3 = $searchModelPaket3->searchPaketS($params, $paket_selesai);
        $dataProviderPaket4 = $searchModelPaket4->searchPaketDp($params, $paket_dalamproses);

        //slider video

        $video = MonitoringSpecFiles::find()->joinWith(['monitoringTask'])->where(['file_extension' => 'mp4', 'fiscal_year' => $this->year])->orderBy(['created_at' => SORT_DESC])->limit(10)->all();
        $countvideo = MonitoringSpecFiles::find()->where(['file_extension' => 'mp4'])->count();

        //slider foto

        $foto = MonitoringSpecFiles::find()->joinWith(['monitoringTask'])->where(['in', 'file_extension', ['jpg', 'jpeg', 'png']])->andWhere(['fiscal_year' => $this->year])->orderBy(['created_at' => SORT_DESC])->limit(10)->all();
        $countfoto = MonitoringSpecFiles::find()->where(['in', 'file_extension', ['jpg', 'jpeg', 'png']])->count();

        return $this->render('index', [
            'searchModelPaket' => $searchModelPaket,
            'searchModelPaket2' => $searchModelPaket2,
            'searchModelPaket3' => $searchModelPaket3,
            'searchModelPaket4' => $searchModelPaket4,
            'dataProviderPaket' => $dataProviderPaket,
            'dataProviderPaket2' => $dataProviderPaket2,
            'dataProviderPaket3' => $dataProviderPaket3,
            'dataProviderPaket4' => $dataProviderPaket4,
            'totaltr' => $totaltr,
            'totaltw' => $totaltw,
            'totals' => $totals,
            'totaldp' => $totaldp,
            'video' => $video,
            'countvideo' => $countvideo,
            'foto' => $foto,
            'countfoto' => $countfoto
        ]);
    }

    /**
     * Set Year.
     *
     * @return string
     */
    public function actionYear()
    {
        $year_list = Year_model::find()->select(['id', 'name'])->where(['active' => 'T'])->orderBy('name')->all();
        $year_list = ArrayHelper::map($year_list, 'id', 'name');

        $model = new DynamicModel(['year']);
        $model->setAttributeLabels(['year' => Yii::t('app', 'Year')]);
        $model->addRule(['year'], 'required');
        $model->addRule(['year'], 'integer');
        if ($this->sess['year']) {
            $model->year = $this->sess['year']['id'];
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $this->sess->remove('year');

            $year = Year_model::findOne($model->year);
            $this->sess['milestone'] = array(
                'ref_sot_id' => $year->ref_sot_id,
                'ref_sot_name' => $year->ref_sot_name,
                'year_id' => $year->id,
                'year_name' => $year->name,
            );
            $this->sess['year'] = array(
                'id' => $year->id,
                'name' => $year->name,
            );

            $this->sess['tahun'] = $year->name;

            $user_check = Yii::$app->user->identity;
            // if (!Yii::$app->user->can('Administrator') && Yii::$app->user->can('Team')) {
            //     $team_user = Teamuser::find()->where(['user_id' => $user_check->id, 'fiscal_year' => $this->sess['year']['name']])->one();
            //     if (!$team_user) {
            //         return $this->redirect('/team/set-team');
            //     } else {
            //         $this->setTeam($team_user);
            //         return $this->redirect('/admin/index-core');
            //     }
            // } else 
            if (!Yii::$app->user->can('Team') && Yii::$app->user->can('OPD')) {
                $user_satker = UserSatker::find()->where(['user_id' => $user_check->id, 'fiscal_year' => $this->sess['year']['name']])->one();
                if (!$user_satker) {
                    return $this->redirect('/satker/set-satker');
                } else {
                    $this->setSatker($user_satker);
                    return $this->redirect('/admin/index-core');
                }
            } else {
                return $this->redirect('/admin/index-core');
            }

            if ($year && !Yii::$app->user->isGuest) {
                return $this->redirect('/admin/index-core');
            }
        }

        return $this->render('year_choose', [
            'model' => $model,
            'dropdown_data' => $year_list
        ]);
    }

    /**
     * Set Pilihan Team to session
     *
     * @return User|null
     */
    public function setTeam($team_user)
    {
        $session   = Yii::$app->session;
        $session['team'] = [
            'team_id'     => $team_user->team_id,
            'team_name'   => Teamlist::findOne($team_user->team_id)->name,
            'user_id'     => $team_user->user_id,
        ];
    }
    /**
     * Set Pilihan OPD to session
     *
     * @return User|null
     */
    public function setSatker($user_satker)
    {
        $session   = Yii::$app->session;
        $session['satker'] = [
            'satker_id'     => $user_satker->satker_id,
            'satker_name'   => Satker::findOne($user_satker->satker_id)->name,
            'user_id'       => $user_satker->user_id,
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndexCore()
    {
        $session = Yii::$app->session;

        if (!$session->has('milestone')) {
            return $this->redirect('/admin/year');
        }

        $params = Yii::$app->request->queryParams;

        $totalk = ConstructionAccident::find()->count();
        $totalks = ConstructionSafety::find()->count();
        $totallow = ConstructionVacancies::find()->count();

        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $id_business_entity = Yii::$app->session['badan_usaha']['id_business_entity'];
            $totalpk = Pbj::find()->joinWith('pbjBusinessEntity')
                ->andWhere(['pbj_business_entity.id_business_entity' => $id_business_entity])
                ->count();
        } else {
            $totalpk = Pbj::find()->count();
        }

        $totalrk = Rup::find()->count();

        $subQuery = (new Query())->select('rup_code')->from('pbj');
        $totalmk = Rup::find()->where(['not in', 'idrup', $subQuery])->count();

        $totaltr = WorkTraining::find()->count();

        $constructionAccidentModel = new ConstructionAccidentSearch();
        $constructionAccidentDataProvider = $constructionAccidentModel->search($params);

        $constructionSafetyModel = new ConstructionSafetySearch();
        $constructionSafetyDataProvider = $constructionSafetyModel->search($params);

        $constructionVacanciesModel = new ConstructionVacanciesSearch();
        $constructionVacanciesDataProvider = $constructionVacanciesModel->searchDashboard($params);

        $pbjModel = new PbjSearch();
        $pbjDataProvider = $pbjModel->search($params);

        $rupModel = new RupSearch();
        $rupDataProvider = $rupModel->search($params);
        $mkModel = new RupSearch();
        $mkDataProvider = $mkModel->search($params, true);
        $trModel = new WorkTrainingSearch();
        $trDataProvider = $trModel->search($params);
        $pagination = new \yii\data\Pagination([
            'totalCount' => $rupDataProvider->query->count(),
            'pageSize' => 10,
        ]);
        $rupDataProvider->pagination = $pagination;
        $pbjDataProvider->pagination = $pagination;
        $mkDataProvider->pagination = $pagination;
        $dataPerTahun = [];
        for ($i = 1; $i <= 12; $i++) {
            $startOfMonth = date("Y-$i-01");
            $endOfMonth = date("Y-$i-t");
            $monthlyCount = HitCounter::find()
                ->select('cookie_mark')
                ->distinct()
                ->where(['between', 'created_at', $startOfMonth, $endOfMonth])
                ->count();
            $dataPerTahun[] = (int) $monthlyCount;
        }
        $totalDataPerTahun = array_sum($dataPerTahun);
        $currentMonth = date("Y-m");
        $dataPerBulan = [];
        $daysInMonth = date("t");
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $specificDate = "$currentMonth-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dailyCount = HitCounter::find()
                ->select('cookie_mark')
                ->distinct()
                ->where(['like', 'created_at', $specificDate . '%', false])
                ->count();
            $dataPerBulan[] = (int) $dailyCount;
        }
        $totalDataPerBulan = array_sum($dataPerBulan);
        return $this->render('index-core', [
            'tahun' => $session['tahun'],
            'constructionAccidentDataProvider' => $constructionAccidentDataProvider,
            'constructionAccidentModel' => $constructionAccidentModel,
            'constructionSafetyDataProvider' => $constructionSafetyDataProvider,
            'constructionSafetyModel' => $constructionSafetyModel,
            'constructionVacanciesDataProvider' => $constructionVacanciesDataProvider,
            'constructionVacanciesModel' => $constructionVacanciesModel,
            'totalk' => $totalk,
            'totalks' => $totalks,
            'totallow' => $totallow,
            'totalpk' => $totalpk,
            'totalrk' => $totalrk,
            'totalmk' => $totalmk,
            'totaltr' => $totaltr,
            'pbjDataProvider' => $pbjDataProvider,
            'pbjModel' => $pbjModel,
            'rupDataProvider' => $rupDataProvider,
            'rupModel' => $rupModel,
            'mkDataProvider' => $mkDataProvider,
            'mkModel' => $mkModel,
            'trDataProvider' => $trDataProvider,
            'trModel' => $trModel,

            //untuk chart pengunjung
            'dataPerTahun' => $dataPerTahun,
            'dataPerBulan' => $dataPerBulan,
            'totalDataPerTahun' => $totalDataPerTahun,
            'totalDataPerBulan' => $totalDataPerBulan,
            'daysInMonth' => $daysInMonth,
        ]);
    }
}
