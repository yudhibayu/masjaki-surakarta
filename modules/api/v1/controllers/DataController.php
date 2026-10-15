<?php

namespace app\modules\api\v1\controllers;

use app\modules\api\v1\models\ApiAccessToken;
use app\modules\api\v1\models\ConstructionVacancies;
use app\modules\api\v1\models\WorkTraining;
use infinindotech\user\filters\AccessRule;
use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `v1` module
 */
class DataController extends Controller
{

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
                        'roles' => ['Administrator', '?', '@'],
                    ],
                ],
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                // 'only' => ['index', 'view'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
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
        $headers = Yii::$app->request->getHeaders();
        $tokenheader = $headers['token'];
        $tokenmodel = DynamicModel::validateData(['tokenheader' => $tokenheader], [
            [['tokenheader'], 'string'],
        ]);
        if ($tokenmodel->hasErrors()) {
            throw new ForbiddenHttpException('not authorized, access token invalid');
        }
        $is_authorized = ApiAccessToken::find()->where(['token' => $tokenheader])->one();
        if ($is_authorized == null) {
            // Yii::$app->response->statusCode = 403;
            // return $this->asJson(['status'=>'not authorized']);
            throw new ForbiddenHttpException('not authorized, access token invalid');
        }
    }

    /**
     * Return he monitoring tasks
     * @return string
     */
    public function actionIndex($year)
    {
        return $this->actionTraining($year);
    }

    public function actionTraining($year)
    {
        if (!$year) {
            throw new BadRequestHttpException("Tahun tidak dipilih");
        }
        $models = WorkTraining::find()->andWhere(['year_name' => $year]);

        $fixeddatas = [];
        foreach ($models->batch() as $rows) {
            foreach ($rows as $row) {
                if ($row instanceof WorkTraining) {
                    $fixedregistrants = [];
                    $registrants = $row->registrant;
                    foreach ($registrants as $registrant) {
                        $fixedregistrants[] = [
                            'id' => $registrant->id_labor,
                            'name' => $registrant->name_labor,
                            'id_status' => $registrant->status,
                            'status' => $registrant->getStatusSelected(),
                            'nik' => $registrant->nik,
                            'subdistrict' => $registrant->subdistrict,
                            'ward' => $registrant->ward,
                        ];
                    }
                    $fixeddatas[] = [
                        'id' => $row->id,
                        'name' => $row->training_name,
                        'quota' => $row->kuota,
                        'start_date' => Yii::$app->formatter->asDate($row->implementation_date, 'php:Y-m-d'),
                        'start_time' => Yii::$app->formatter->asTime($row->implementation_time),
                        'finish_date' => Yii::$app->formatter->asDate($row->finish_date, 'php:Y-m-d'),
                        'finish_time' => Yii::$app->formatter->asTime($row->finish_time),
                        'person_responsible' => $row->person_responsible_name,
                        'id_skills' => $row->skills,
                        'skills' => isset($row->skillsRelation->name) ? $row->skillsRelation->name : $row->skills,
                        'description' => $row->description,
                        'country' => $row->country,
                        'province' => $row->province,
                        'regency' => $row->regency,
                        'subdistrict' => $row->subdistrict,
                        'year' => $row->year_name,
                        'id_publication' => $row->publication,
                        'publication' => $row->getStatusPublication(),
                        'ward' => $row->ward,
                        'registrants' => $fixedregistrants,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ];
                }
            }
        }
        return $fixeddatas;
    }

    public function actionVacancies($year)
    {
        if (!$year) {
            throw new BadRequestHttpException("Tahun tidak dipilih");
        }
        $models = ConstructionVacancies::find()->andWhere(['year_name' => $year]);

        $fixeddatas = [];
        foreach ($models->batch() as $rows) {
            foreach ($rows as $row) {
                if ($row instanceof ConstructionVacancies) {
                    $fixedregistrants = [];
                    $registrants = $row->registrant;
                    foreach ($registrants as $registrant) {
                        $fixedregistrants[] = [
                            'id' => $registrant->id_labor,
                            'name' => $registrant->name_labor,
                            'id_status' => $registrant->status,
                            'status' => $registrant->getStatusSelected(),
                            'nik' => $registrant->nik,
                            'subdistrict' => $registrant->subdistrict,
                            'ward' => $registrant->ward,
                        ];
                    }
                    $fixeddatas[] = [
                        'id' => $row->id,
                        'name' => $row->name,
                        'description' => $row->description,
                        'id_construction_work' => $row->construction_work,
                        'construction_work' => isset($row->constructionWork->name) ? $row->constructionWork->name : $row->construction_work,
                        'id_skills' => $row->skills,
                        'skills' => isset($row->skillsRelation->name) ? $row->skillsRelation->name : $row->skills,
                        'id_education' => $row->education,
                        'education' => isset($row->educationRelation->name) ? $row->educationRelation->name : $row->education,
                        'age_range_from' => $row->age_range,
                        'age_range_upto' => $row->age_range_upto,
                        'work_experience' => $row->work_experience,
                        'quota' => $row->quota,
                        'salary_range_from' => $row->salary_range,
                        'salary_range_upto' => $row->salary_range_upto,
                        'start_date' => Yii::$app->formatter->asDate($row->start_date,'php:Y-m-d'),
                        'work_start_time' => $row->start_time,
                        'work_end_time' => $row->end_time,
                        'date_of_completion' => Yii::$app->formatter->asDate($row->date_of_completion,'php:Y-m-d'),
                        'id_type_of_work' => (int)$row->type_of_work,
                        'type_of_work' => $row->getWorkType(),
                        'id_publication' => $row->publication,
                        'publication' => $row->getStatusPublication(),
                        'id_year' => $row->id_year,
                        'year_name' => $row->year_name,
                        'nama_business_entity' => $row->nama_business_entity,
                        'id_business_entity' => $row->id_business_entity,
                        'registrants' => $fixedregistrants,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ];
                }
            }
        }
        return $fixeddatas;
    }
}
