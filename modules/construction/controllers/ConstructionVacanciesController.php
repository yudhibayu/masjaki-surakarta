<?php

namespace app\modules\construction\controllers;

use Yii;

use DateTime;
use DateInterval;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\rup\models\Pbj;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\construction\models\Labor;
use app\modules\references\models\Year_model;
use app\modules\referencesMasjaki\models\Skills;
use app\modules\referencesMasjaki\models\Education;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\construction\models\ConstructionWork;
use app\modules\construction\models\PbjBusinessEntity;
use app\modules\construction\models\RegistrantVacancies;
use app\modules\construction\models\ConstructionVacancies;
use app\modules\construction\models\RegistrantVacanciesSearch;
use app\modules\construction\models\ConstructionVacanciesSearch;

/**
 * ConstructionVacanciesController implements the CRUD actions for ConstructionVacancies model.
 */
class ConstructionVacanciesController extends Controller
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
                        'roles' => ['administrator', 'Badan Usaha'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'register'],
                        'roles' => ['Tenaga Kerja'],
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

        $education = Education::find()->orderBy('name')->all();
        $this->dropdown_data['education'] = ArrayHelper::map($education, 'id', 'name');
        $skills = Skills::find()->orderBy('name')->all();
        $this->dropdown_data['skills'] = ArrayHelper::map($skills, 'id', 'name');
        $id_business = BusinessEntity::find()->where(['account_verification' => BusinessEntity::VERIFICATION])->orderBy('company_name')->all();
        $this->dropdown_data['id_business_entity'] = ArrayHelper::map($id_business, 'id', 'company_name');
        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $idBusinessEntity = Yii::$app->session['badan_usaha']['id_business_entity'];
            $kodelelangList = PbjBusinessEntity::find()
                ->select('kode_pbj')
                ->where(['id_business_entity' => $idBusinessEntity])
                ->column();
            $construction_work = Pbj::find()
                ->where(['in', 'kodelelang', $kodelelangList])
                ->orderBy('name')
                ->all();
            $this->dropdown_data['construction_work'] = ArrayHelper::map($construction_work, 'kodelelang', 'name');
        } else {
            $construction_work = Pbj::find()->orderBy('name')->all();
            $this->dropdown_data['construction_work'] = ArrayHelper::map($construction_work, 'kodelelang', 'name');
        }
        $ref_year = Year_model::find()->orderBy('name')->all();
        $this->dropdown_data['ref_year'] = ArrayHelper::map($ref_year, 'id', 'name');
    }

    // public function actionConstructionWorkDetails($id)
    // {

    //     $id = Yii::$app->hasher->decode($id);
    //     // return $this->redirect(['index']);
    //     $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $id]));

    //     // return $this->asJson($id);


    //     // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     // Cari construction work berdasarkan ID
    //     $constructionWork = ConstructionWork::findOne($id);

    //     if ($constructionWork) {

    //         // Ambil detail yang diperlukan dari construction work
    //         $businessEntity = $constructionWork->businessEntity;
    //         $sourceOfFunds = $constructionWork->sourceOfFunds;
    //         $laborWinner = $constructionWork->laborWinner;

    //         $details = [
    //             'company_name' => $businessEntity ? $businessEntity->company_name : '',
    //             'source_of_funds' => $sourceOfFunds ? $sourceOfFunds->name : '',
    //             'winner' => $laborWinner ? $laborWinner->name : '',
    //         ];

    //         return $details;
    //     } else {
    //         return ['error' => 'Construction work not found'];
    //     }
    // }

    /**
     * Lists all ConstructionVacancies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        if (count($params) <= 1) {
            $params = $this->sess['construction_vacancies'];
            if (isset($this->sess['construction_vacancies']['page']))
                $_GET['page'] = $this->sess['construction_vacancies']['page'];
        } else {
            $this->sess['construction_vacancies'] = $params;
        }
        $searchModel = new ConstructionVacanciesSearch();
        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $dataProvider = $searchModel->search($params, null, null, Yii::$app->session['badan_usaha']['id_business_entity']);
            $dataProviderPublicated = $searchModel->search($params, $searchModel::PUBLICATED, null, Yii::$app->session['badan_usaha']['id_business_entity']);
            $dataProviderUnpublicated = $searchModel->search($params, $searchModel::UNPUBLICATED, null, Yii::$app->session['badan_usaha']['id_business_entity']);
            $dataProviderClosed = $searchModel->search($params, $searchModel::CLOSED, null, Yii::$app->session['badan_usaha']['id_business_entity']);
        } else {
            $dataProvider = $searchModel->search($params);
            $dataProviderPublicated = $searchModel->search($params, $searchModel::PUBLICATED);
            $dataProviderUnpublicated = $searchModel->search($params, $searchModel::UNPUBLICATED);
            $dataProviderClosed = $searchModel->search($params, $searchModel::CLOSED);
        }
        $dataProviderLabor = $searchModel->search($params, null, $searchModel::UNPUBLICATED);
        Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderPublicated' => $dataProviderPublicated,
            'dataProviderUnpublicated' => $dataProviderUnpublicated,
            'dataProviderClosed' => $dataProviderClosed,
            'dataProviderLabor' => $dataProviderLabor,
        ]);
    }
    /**
     * Creates a new ConstructionVacancies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConstructionVacancies();
        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $model->id_business_entity = isset(Yii::$app->session['badan_usaha']['id_business_entity']) ? Yii::$app->session['badan_usaha']['id_business_entity'] : '';
            $businessEntity = BusinessEntity::findOne(Yii::$app->session['badan_usaha']['id_business_entity']);
            $model->nama_business_entity = $businessEntity->company_name;
        }
        $model->year_name = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
        $model->start_date = date('d-m-Y');
        $date = new DateTime();
        $date->add(new DateInterval('P30D'));
        $model->date_of_completion = $date->format('d-m-Y');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->id_business_entity) {
                // var_dump($model->id_business_entity);
                // exit();
                $businessEntity = BusinessEntity::findOne(['id' => $model->id_business_entity]);
                $model->nama_business_entity = $businessEntity->company_name;
            }
            if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
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
     * Displays a single ConstructionVacancies model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;
        $searchModel = new RegistrantVacanciesSearch();
        $dataProvider = $searchModel->search($params, $id, [RegistrantVacancies::UNSELECTED, RegistrantVacancies::NOTSELECTED]);
        $dataProviderSelect = $searchModel->search($params, $id, RegistrantVacancies::SELECTED);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderSelect' => $dataProviderSelect
        ]);
    }

    public function actionRegister($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if (isset(Yii::$app->session['tenaga_kerja']['id_labor'])) {
            $labor = Yii::$app->session['tenaga_kerja']['id_labor'];
            if ($model->publication == $model::PUBLICATED) {
                $existingRegistrant = RegistrantVacancies::find()
                    ->where(['id_vacancies' => $model->id, 'id_labor' => $labor])
                    ->exists();
                if ($existingRegistrant) {
                    Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, anda sudah mendaftar di lowongan ini.');
                } else {
                    $registrant = new RegistrantVacancies();
                    $registrant->id_vacancies = $model->id;
                    $registrant->id_labor = $labor;
                    $registrant->save();
                    $this->sess->setFlash('success', Yii::t('app', 'Anda Berhasil terdaftarkan'));
                }
            } else {
                Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, Lowongan tidak terpublikasi');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, Anda bukan Tenaga Kerja');
        }

        return $this->redirect(['index']);
    }

    public function actionPublication($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if (!$model->publication) {
            $model->publication = $model::PUBLICATED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been publicated.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> closed', ['name' => $model->name]));
        }

        return $this->redirect(['index']);
    }

    public function actionUnpublication($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->publication == $model::PUBLICATED) {
            $model->publication = $model::UNPUBLICATED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> unpublicated.', ['name' => $model->name]));
        } else {
            if ($model->publication == 0) {
                $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> never publicated.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> closed', ['name' => $model->name]));
            }
        }

        return $this->redirect(['index']);
    }

    public function actionClosed($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->publication == $model::PUBLICATED) {
            $model->publication = $model::CLOSED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been closed.', ['name' => $model->name]));
        } else {
            if ($model->publication == $model::UNPUBLICATED) {
                $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> never publicated.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> closed', ['name' => $model->name]));
            }
        }

        return $this->redirect(['index']);
    }
    public function actionOpen($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->publication == $model::CLOSED) {
            $model->publication = $model::PUBLICATED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been open (publicated!).', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> never closed.', ['name' => $model->name]));
        }

        return $this->redirect(['index']);
    }
    /**
     * Updates an existing ConstructionVacancies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->id_business_entity) {
                $businessEntity = BusinessEntity::findOne(['id' => $model->id_business_entity]);
                $model->nama_business_entity = $businessEntity->company_name;
            } else {
                $model->id_business_entity = null;
                $model->nama_business_entity = null;
            }
            if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
            }
        }

        $this->populate_dropdown();

        if ($model->publication == 0) {
            return $this->render('update', [
                'model'         => $model,
                'dropdown_data' => $this->dropdown_data,
                'url_prev'      => Url::previous()
            ]);
        } else {
            if ($model->publication == 1) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed. Vacancies has been publicated'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed. Vacancies has been closed'));
            }
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing ConstructionVacancies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $labor = RegistrantVacancies::find()->where(['id_vacancies' => $id])->all();

        if (!empty($labor)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Data cannot be deleted because there is child data inside.'));
        } else {
            if ($model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ConstructionVacancies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConstructionVacancies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConstructionVacancies::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('construction_vacancies');
        return $this->redirect(['index']);
    }
}
