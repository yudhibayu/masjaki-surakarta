<?php

namespace app\modules\construction\controllers;

use app\modules\construction\models\ConstructionVacancies;
use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\construction\models\Labor;
use app\modules\construction\models\WorkTraining;
use app\modules\construction\models\RegistrantTraining;
use app\modules\construction\models\RegistrantTrainingSearch;

/**
 * RegistrantTrainingController implements the CRUD actions for RegistrantTraining model.
 */
class RegistrantTrainingController extends Controller
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
                        'roles' => ['Badan Usaha'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index-labor'],
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

        $id_training = WorkTraining::find()->where(['publication' => WorkTraining::PUBLICATED])->orderBy('training_name')->all();
        $this->dropdown_data['id_training'] = ArrayHelper::map($id_training, 'id', 'training_name');
    }

    /**
     * Lists all RegistrantTraining models.
     * @return mixed
     */
    public function actionIndexLabor()
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['registrant_vacancies'];
            if (isset($this->sess['registrant_vacancies']['page']))
                $_GET['page'] = $this->sess['registrant_vacancies']['page'];
        } else {
            $this->sess['registrant_vacancies'] = $params;
        }
        $labor = Yii::$app->session['tenaga_kerja']['id_labor'];
        $searchModel = new RegistrantTrainingSearch();
        $dataProvider = $searchModel->search($params, null, null, null, $labor);

        Url::remember();

        return $this->render('index-labor', [
            'searchModelTraining' => $searchModel,
            'dataProviderTraining' => $dataProvider,
        ]);
    }
    // public function actionIndex($id = null)
    // {

    //     $id = Yii::$app->hasher->decode($id);
    //     $params = Yii::$app->request->queryParams;
    //     $model = WorkTraining::find()->where(['id' => $id])->one();

    //     if (count($params) <= 1) {
    //         $params = $this->sess['registrant_training'];
    //         if (isset($this->sess['registrant_training']['page']))
    //             $_GET['page'] = $this->sess['registrant_training']['page'];
    //     } else {
    //         $this->sess['registrant_training'] = $params;
    //     }

    //     $searchModel = new RegistrantTrainingSearch();
    //     $dataProvider = $searchModel->search($params, $id);

    //     Url::remember();

    //     if ($model->publication !== 0) {
    //         return $this->render('index', [
    //             'searchModel' => $searchModel,
    //             'dataProvider' => $dataProvider,
    //         ]);
    //     } else {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'Show data failed. Training never publicated'));
    //         return $this->redirect(['/construction/work-training/index']);
    //     }
    // }
    public function actionNotselect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->training->publication == $model->training::PUBLICATED) {
            $model->status = $model::NOTSELECTED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Pekerja Tidak Dipillih'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Pelatihan telah ditutup'));
        }

        return $this->redirect(['/construction/work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)]);
    }
    public function actionUndonotselect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->training->publication == $model->training::PUBLICATED) {
            $model->status = $model::UNSELECTED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Pekerja Berhasil Dipulihkan'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Pelatihan telah ditutup'));
        }

        return $this->redirect(['/construction/work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)]);
    }
    public function actionSelect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $idTraining = $model->id_training;

        if ($model->training->publication == $model->training::PUBLICATED) {
            if (!$model->status) {
                $trainingQuota = WorkTraining::find()->where(['id' => $model->id_training])->one();
                if ($trainingQuota !== null) {
                    $selectedCount = RegistrantTraining::find()
                        ->where(['status' => $model::SELECTED, 'id_training' => $model->id_training])
                        ->count();
                    if ($selectedCount < $trainingQuota->kuota) {
                        $model->status = $model::SELECTED;
                        if (!$model->save(false)) {
                            throw new ServerErrorHttpException('Cannot save data');
                        }
                        if ($selectedCount == $trainingQuota->kuota - 1) {
                            RegistrantTraining::updateAll(['status' => 2], [
                                'id_training' => $idTraining,
                                'status' => 0,
                            ]);
                        }
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been selected.', ['name' => $model->name_labor]));
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Kuota pekerja sudah habis'));
                    }
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Kuota training tidak dibuat'));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Tenaga kerja sudah dipilih'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Training telah ditutup'));
        }

        return $this->redirect(['/construction/work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)]);
    }

    public function actionUnselect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        if ($model->training->publication == $model->training::PUBLICATED) {
            if ($model->status == $model::SELECTED) {
                $model->status = $model::UNSELECTED;
                if (!$model->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been unselected.', ['name' => $model->name_labor]));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Tenaga kerja tidak terpilih'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Lowongan telah ditutup'));
        }

        return $this->redirect(['/construction/work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)]);
    }
    /**
     * Displays a single RegistrantTraining model.
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
     * Creates a new RegistrantTraining model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new RegistrantTraining();
        $model->id_training = $id;
        $training = WorkTraining::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $existingRegistrant = RegistrantTraining::find()
                ->where(['id_training' => $model->id_training, 'id_labor' => $model->id_labor])
                ->exists();
            if ($existingRegistrant) {
                Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, user sudah mendaftar di pelatihan ini.');
            } else {
                if ($model->save()) {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been register.', ['name' => $model->name_labor]));
                    return $this->redirect(['/construction/work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)]);
                }
            }
        }

        $this->populate_dropdown();
        $id_labor_register = RegistrantTraining::find()->select('id_labor')->where(['id_training' => $id])->column();
        $id_labor = Labor::find()->where(['not', ['id' => $id_labor_register]])->andWhere(['account_verification' => 1])->orderBy('name')->all();
        $this->dropdown_data['id_labor'] = ArrayHelper::map($id_labor, 'id', 'name');

        if ($training->publication == 1) {
            return $this->render('create', [
                'model'         => $model,
                'dropdown_data' => $this->dropdown_data,
                'url_prev'      => Url::previous()
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'Pelatihan tidak sedang dipublikasi');
            return $this->redirect(['/construction/work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)]);
        }
    }

    /**
     * Updates an existing RegistrantTraining model.
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
    //         $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name_labor]));
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
     * Deletes an existing RegistrantTraining model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     $model = $this->findModel($id);
    //     if ($model->delete()) {
    //         $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name_labor]));
    //     } else {
    //         $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name_labor]));
    //     }

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the RegistrantTraining model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistrantTraining the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistrantTraining::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('registrant_training');
        return $this->redirect(['index']);
    }
}
