<?php

namespace app\controllers;

use yii\web\Session;
use yii\web\Response;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessRule;
use yii\filters\AccessControl;
use yii\base\Theme;
use yii\base\DynamicModel;
use app\overrides\user\models\LoginForm;
use app\modules\manage_scope\models\RefScope;
use app\models\ContactForm;
use app\components\Theming;
use app\models\Satker;
use app\models\Teamlist;
use app\models\Teamuser;
use app\models\UserSatker;
use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\references\models\Year_model;
use Yii;
use yii\data\ArrayDataProvider;

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
    public function actionIndex($year = null)
    {
        $session = Yii::$app->session;

        // if (!Yii::$app->user->can('Team') && !$session->has('unit')){
        //     return $this->redirect('/manage_unit/user-unit/unit');
        // } 
        if (!$session->has('milestone')) {
            return $this->redirect('/admin/year');
        }

        $monthlymonitor = $this->getMonthlyMonitor();
        $totalbymonthprovider = $this->getTotalByMonthProvider($monthlymonitor);
        $statDataPaket = $this->statDataPaket();

        return $this->render('index_old', [
            'monthlymonitor' => $monthlymonitor,
            'totalbymonthprovider' => $totalbymonthprovider,
            'statdatapaket' => $statDataPaket,
        ]);
    }

    private function statDataPaket()
    {
        $paketterm = MonitoringTask::find()->where(['fiscal_year' => $this->year])
            ->andFilterWhere(["unit_id" => $this->unit_id])
            ->andFilterWhere(["team_id" => $this->team_id]);
        $countpaket = clone $paketterm;
        $countpaket = $countpaket->count('id');
        $sumkeuplanning = clone $paketterm;
        $sumkeuplanning = $sumkeuplanning->sum('latest_plan_financial_budget');
        $sumkeurealisasi = clone $paketterm;
        $sumkeurealisasi = $sumkeurealisasi->sum('latest_monitored_financial_budget');
        $avgfisiktarget = clone $paketterm;
        $avgfisiktarget = $avgfisiktarget->average('latest_plan_physical_percent');
        $avgfisikrealisasi = clone $paketterm;
        $avgfisikrealisasi = $avgfisikrealisasi->average('latest_monitored_physical_percent');
        $statuses = MonitoringTask::implementationStatusNames();
        $statusesdata = [];
        foreach ($statuses as $key => $value) {
            $stat = clone $paketterm;
            $stat = $stat->andWhere(['implementation_status' => $key])->count('id');
            $statusesdata[$key] = $stat;
        }
        return [
            'jumlahpaket' => $countpaket,
            'keuangan' => [
                'target' => $sumkeuplanning,
                'realisasi' => $sumkeurealisasi,
            ],
            'fisik' => [
                'target' => $avgfisiktarget,
                'realisasi' => $avgfisikrealisasi,
            ],
            'statuses' => $statusesdata,
        ];
    }

    private function getTotalByMonthProvider($monthlymonitor)
    {
        $totalbymonth = [];
        if (isset(Yii::$app->indodatetime)) {
            $indodatetime = Yii::$app->indodatetime;
        }
        for ($i = 1; $i <= 12; $i++) {
            if ($indodatetime) {
                $month = $indodatetime->getBulan($i);
            } else {
                $month = date('F');
            }
            $monthly = $monthlymonitor[$i]['total'];
            $monthly['month'] = $i;
            $monthly['month_name'] = $month;
            $monthly['target_keu_percent'] = ((float)$monthly['price'] > 0) ? ((float)$monthly['target_keu'] / (float)$monthly['price'] * 100) : 0;
            $monthly['realisasi_keu_percent'] = ((float)$monthly['price'] > 0) ? ((float)$monthly['realisasi_keu'] / (float)$monthly['price'] * 100) : 0;
            $totalbymonth[] = $monthly;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $totalbymonth,
            'sort' => [
                'attributes' => [
                    'price',
                    'target_keu',
                    'realisasi_keu',
                    'target_fisik',
                    'realisasi_fisik',
                    'target_keu_percent',
                    'realisasi_keu_percent',
                    'month',
                ],
            ],
            'pagination' => [
                'pageSize' => 12,
            ]
        ]);

        return $dataProvider;
    }

    private function getMonthlyMonitor()
    {
        $monthlymonitor = [];
        $task_ids = MonitoringTask::find()->select('id')->distinct()->where(['fiscal_year' => $this->year])->column();
        for ($i = 1; $i <= 12; $i++) {
            $tsktbl = MonitoringTask::tableName();
            $monitorings = MonitoringProgress::find()
                ->joinWith('monitoringTask')
                ->select([
                    'monitoring_task_id',
                    'price' => "$tsktbl.final_price",
                    'order_no' => 'MAX(order_no)',
                    'target_keu' => 'MAX(planned_financial_budget)',
                    'realisasi_keu' => 'MAX(financial_budget)',
                    'target_fisik' => 'MAX(planned_physical_percent)',
                    'realisasi_fisik' => 'MAX(physical_percent)',
                ])
                ->andWhere(['date_month' => $i, 'fiscal_year' => $this->year])
                ->andFilterWhere(["$tsktbl.unit_id" => $this->unit_id])
                ->andFilterWhere(["$tsktbl.team_id" => $this->team_id])
                ->groupBy('monitoring_task_id')
                ->createCommand()->queryAll();
            $monitorarr = [];
            foreach ($monitorings as $monitoring) {
                $monitorarr[$monitoring['monitoring_task_id']] = $monitoring;
            }
            $monthlymonitor[$i] = $monitorarr;
        }
        $monthlymonitor = $this->carryOldData($monthlymonitor, $task_ids);
        $totalbymonth = $this->sumMonthlyData($monthlymonitor);
        for ($i = 1; $i <= 12; $i++) {
            $monthlymonitor[$i]['total'] = $totalbymonth[$i];
        }
        return $monthlymonitor;
    }

    private function carryOldData($monthlymonitor, $task_ids)
    {
        $zerodata = [
            'order_no' => 0,
            'price' => 0,
            'target_keu' => "0",
            'realisasi_keu' => "0",
            'target_fisik' => "0",
            'realisasi_fisik' => "0",
        ];
        for ($i = 1; $i <= 12; $i++) {
            if ($i == 1) {
                foreach ($task_ids as $task_id) {
                    if (!isset($monthlymonitor[$i][$task_id])) {
                        $zerodata['monitoring_task_id'] = $task_id;
                        $monthlymonitor[$i][$task_id] = $zerodata;
                    }
                }
            } else {
                foreach ($task_ids as $task_id) {
                    if (!isset($monthlymonitor[$i][$task_id])) {
                        $monthlymonitor[$i][$task_id] = $monthlymonitor[$i - 1][$task_id];
                    }
                }
            }
        }
        return $monthlymonitor;
    }

    private function sumMonthlyData($monthlymonitor)
    {
        $zerodata = [
            'order_no' => 0,
            'price' => 0,
            'target_keu' => "0",
            'realisasi_keu' => "0",
            'target_fisik' => "0",
            'realisasi_fisik' => "0",
        ];
        for ($i = 1; $i <= 12; $i++) {
            $totalbymonth[$i]['target_keu'] = array_sum(array_column($monthlymonitor[$i], 'target_keu'));
            $totalbymonth[$i]['price'] = array_sum(array_column($monthlymonitor[$i], 'price'));
            $totalbymonth[$i]['realisasi_keu'] = array_sum(array_column($monthlymonitor[$i], 'realisasi_keu'));
            if (sizeof($monthlymonitor[$i]) <= 0) {
                $totalbymonth[$i]['target_fisik'] = 0;
                $totalbymonth[$i]['realisasi_fisik'] = 0;
            } else {
                $totalbymonth[$i]['target_fisik'] = array_sum(array_column($monthlymonitor[$i], 'target_fisik')) / sizeof($monthlymonitor[$i]);
                $totalbymonth[$i]['realisasi_fisik'] = array_sum(array_column($monthlymonitor[$i], 'realisasi_fisik')) / sizeof($monthlymonitor[$i]);
            }
        }
        return $totalbymonth;
    }

    /**
     * Set Year.
     *
     * @return string
     */
    public function actionYear()
    {
        $year_list = Year_model::find()->select(['id', 'name'])->where(['active' => 'T'])->orderBy('id')->all();
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
            if (!Yii::$app->user->can('Administrator') && Yii::$app->user->can('Team')) {
                $team_user = Teamuser::find()->where(['user_id' => $user_check->id, 'fiscal_year' => $this->sess['year']['name']])->one();
                if (!$team_user) {
                    return $this->redirect('/team/set-team');
                } else {
                    $this->setTeam($team_user);
                    return $this->redirect('/admin');
                }
            } else if (!Yii::$app->user->can('Team') && Yii::$app->user->can('OPD')) {
                $user_satker = UserSatker::find()->where(['user_id' => $user_check->id, 'fiscal_year' => $this->sess['year']['name']])->one();
                if (!$user_satker) {
                    return $this->redirect('/satker/set-satker');
                } else {
                    $this->setSatker($user_satker);
                    return $this->redirect('/admin');
                }
            } else {
                return $this->redirect('/admin');
            }

            if ($year && !Yii::$app->user->isGuest) {
                return $this->redirect('/admin');
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
}
