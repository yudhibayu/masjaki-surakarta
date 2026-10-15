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
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\construction\models\Labor;
use app\modules\references\models\Year_model;
use app\modules\referencesMasjaki\models\Skills;
use app\modules\construction\models\WorkTraining;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\construction\models\RegistrantTraining;
use app\modules\construction\models\RegistrantTrainingSearch;
use app\modules\construction\models\WorkTrainingSearch;
use app\modules\construction\models\RegistrantVacancies;

/**
 * WorkTrainingController implements the CRUD actions for WorkTraining model.
 */
class WorkTrainingController extends Controller
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
                        'roles' => ['Administrator'],
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

        $skills = Skills::find()->orderBy('name')->all();
        $this->dropdown_data['skills'] = ArrayHelper::map($skills, 'id', 'name');

        $ref_year = Year_model::find()->orderBy('name')->all();
        $this->dropdown_data['ref_year'] = ArrayHelper::map($ref_year, 'id', 'name');
        $ref_negara = RefNegara::find()->orderBy('name')->all();
        $this->dropdown_data['ref_negara'] = ArrayHelper::map($ref_negara, 'id', 'name');
        //monggo


    }

    /**
     * Lists all WorkTraining models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['work_training'];
            if (isset($this->sess['work_training']['page']))
                $_GET['page'] = $this->sess['work_training']['page'];
        } else {
            $this->sess['work_training'] = $params;
        }

        $searchModel = new WorkTrainingSearch();
        $dataProvider = $searchModel->search($params);
        $dataProviderPublicated = $searchModel->search($params, $searchModel::PUBLICATED);
        $dataProviderUnpublicated = $searchModel->search($params, $searchModel::UNPUBLICATED);
        $dataProviderClosed = $searchModel->search($params, $searchModel::CLOSED);
        $dataProviderLabor = $searchModel->search($params, null, $searchModel::UNPUBLICATED);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderPublicated' => $dataProviderPublicated,
            'dataProviderUnpublicated' => $dataProviderUnpublicated,
            'dataProviderLabor' => $dataProviderLabor,
            'dataProviderClosed' => $dataProviderClosed
        ]);
    }

    /**
     * Displays a single WorkTraining model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);


        $params = Yii::$app->request->queryParams;
        $searchModel = new RegistrantTrainingSearch();
        $dataProvider = $searchModel->search($params, $id, [RegistrantTraining::UNSELECTED, RegistrantTraining::NOTSELECTED]);
        $dataProviderSelect = $searchModel->search($params, $id, RegistrantTraining::SELECTED);
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
                $existingRegistrant = RegistrantTraining::find()
                    ->where(['id_training' => $model->id, 'id_labor' => $labor])
                    ->exists();
                if ($existingRegistrant) {
                    Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, anda sudah mendaftar di pelatihan ini.');
                } else {
                    $registrant = new RegistrantTraining();
                    $registrant->id_training = $model->id;
                    $registrant->id_labor = $labor;
                    $registrant->save();
                    $this->sess->setFlash('success', Yii::t('app', 'Anda Berhasil terdaftarkan'));
                }
            } else {
                Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, Pelatihan tidak terpublikasi');
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
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been publicated.', ['name' => $model->training_name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> closed', ['name' => $model->training_name]));
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
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> unpublicated.', ['name' => $model->training_name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> closed', ['name' => $model->training_name]));
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
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been closed.', ['name' => $model->training_name]));
        } else {
            if ($model->publication == $model::UNPUBLICATED) {
                $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> never publicated.', ['name' => $model->training_name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> closed', ['name' => $model->training_name]));
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
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been open (publicated!).', ['name' => $model->training_name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record <strong>{name}</strong> never closed.', ['name' => $model->training_name]));
        }

        return $this->redirect(['index']);
    }


    /**
     * Creates a new WorkTraining model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorkTraining();
        $model->id_country = 100;
        $model->id_business_entity = isset(Yii::$app->session['badan_usaha']['id_business_entity']) ? Yii::$app->session['badan_usaha']['id_business_entity'] : '';
        $model->year_name = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];

        $model->implementation_date = date('d-m-Y');
        $date = new DateTime();
        $date->add(new DateInterval('P30D'));
        $model->finish_date = $date->format('d-m-Y');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->training_name]));
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
     * Updates an existing WorkTraining model.
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
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->training_name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
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
                Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed. Training has been publicated'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed. Training has been closed'));
            }
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing WorkTraining model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $labor = RegistrantTraining::find()->where(['id_training' => $id])->all();

        if (!empty($labor)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Data cannot be deleted because there is child data inside.'));
        } else {
            if ($model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->training_name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->training_name]));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the WorkTraining model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkTraining the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkTraining::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('work_training');
        return $this->redirect(['index']);
    }
}
