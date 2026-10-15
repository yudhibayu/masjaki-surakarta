<?php

namespace app\modules\construction\controllers;

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
use app\modules\construction\models\RegistrantVacancies;
use app\modules\construction\models\ConstructionVacancies;
use app\modules\construction\models\RegistrantVacanciesSearch;

/**
 * RegistrantVacanciesController implements the CRUD actions for RegistrantVacancies model.
 */
class RegistrantVacanciesController extends Controller
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

        $id_vacancies = ConstructionVacancies::find()->where(['publication' => ConstructionVacancies::PUBLICATED])->orderBy('name')->all();
        $this->dropdown_data['id_vacancies'] = ArrayHelper::map($id_vacancies, 'id', 'name');

        //monggo


    }

    /**
     * Lists all RegistrantVacancies models.
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
        $searchModel = new RegistrantVacanciesSearch();
        $dataProvider = $searchModel->search($params, null, null, null, $labor);

        Url::remember();

        return $this->render('index-labor', [
            'searchModelVacancies' => $searchModel,
            'dataProviderVacancies' => $dataProvider,
        ]);
    }
    // public function actionIndex($id = null)
    // {

    //     $id = Yii::$app->hasher->decode($id);
    //     $params = Yii::$app->request->queryParams;
    //     $model = ConstructionVacancies::find()->where(['id' => $id])->one();

    //     if (count($params) <= 1) {
    //         $params = $this->sess['registrant_vacancies'];
    //         if (isset($this->sess['registrant_vacancies']['page']))
    //             $_GET['page'] = $this->sess['registrant_vacancies']['page'];
    //     } else {
    //         $this->sess['registrant_vacancies'] = $params;
    //     }

    //     $searchModel = new RegistrantVacanciesSearch();
    //     $dataProvider = $searchModel->search($params, $id);

    //     Url::remember();

    //     if ($model->publication !== 0) {
    //         return $this->render('index', [
    //             'searchModel' => $searchModel,
    //             'dataProvider' => $dataProvider,
    //         ]);
    //     } else {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'Show data failed. Lowongan never publicated'));
    //         return $this->redirect(['/construction/construction-vacancies/index']);
    //     }
    // }

    // public function actionSelect($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);
    //     $model = $this->findModel($id);

    //     if ($model->vacancies->publication == 1) {
    //         if (!$model->status) {
    //             $model->status = 1;
    //             if (!$model->save(false)) {
    //                 throw new ServerErrorHttpException('Cannot save data');
    //             }
    //             $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been selected.', ['name' => $model->name_labor]));
    //         } else {
    //             Yii::$app->session->setFlash('error', Yii::t('app', 'Tenaga kerja sudah dipilih'));
    //         }
    //     } else {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'Lowongan is closed'));
    //     }
    //     return $this->redirect(['index', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
    // }

    public function actionNotselect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->vacancies->publication == $model->vacancies::PUBLICATED) {
            $model->status = $model::NOTSELECTED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Pekerja Tidak Dipillih'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Lowongan telah ditutup'));
        }

        return $this->redirect(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
    }
    public function actionUndonotselect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->vacancies->publication == $model->vacancies::PUBLICATED) {
            $model->status = $model::UNSELECTED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Pekerja Berhasil Dipulihkan'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Lowongan telah ditutup'));
        }

        return $this->redirect(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
    }
    public function actionSelect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $idVacancies = $model->id_vacancies;

        if ($model->vacancies->publication == $model->vacancies::PUBLICATED) {
            if (!$model->status) {
                $vacanciesQuota = ConstructionVacancies::find()->where(['id' => $model->id_vacancies])->one();
                if ($vacanciesQuota !== null) {
                    $selectedCount = RegistrantVacancies::find()
                        ->where(['status' => $model::SELECTED, 'id_vacancies' => $model->id_vacancies])
                        ->count();
                    if ($selectedCount < $vacanciesQuota->quota) {
                        $model->status = $model::SELECTED;
                        if (!$model->save(false)) {
                            throw new ServerErrorHttpException('Cannot save data');
                        }
                        if ($selectedCount == $vacanciesQuota->quota - 1) {
                            RegistrantVacancies::updateAll(['status' => 2], [
                                'id_vacancies' => $idVacancies,
                                'status' => 0,
                            ]);
                        }
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been selected.', ['name' => $model->name_labor]));
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Kuota pekerja sudah habis'));
                    }
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Kuota lowongan tidak dibuat'));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Tenaga kerja sudah dipilih'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Lowongan telah ditutup'));
        }

        return $this->redirect(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
    }
    public function actionUnselect($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        if ($model->vacancies->publication == $model->vacancies::PUBLICATED) {
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

        return $this->redirect(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
    }
    /**
     * Displays a single RegistrantVacancies model.
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
     * Creates a new RegistrantVacancies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new RegistrantVacancies();
        $model->id_vacancies = $id;
        $vacancies = ConstructionVacancies::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $existingRegistrant = RegistrantVacancies::find()
                ->where(['id_vacancies' => $model->id_vacancies, 'id_labor' => $model->id_labor])
                ->exists();
            if ($existingRegistrant) {
                Yii::$app->session->setFlash('error', 'Tidak dapat mendaftar, user sudah mendaftar di lowongan ini.');
            } else {
                if ($model->save()) {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been register.', ['name' => $model->name_labor]));
                    return $this->redirect(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
                }
            }
        }

        $this->populate_dropdown();

        $id_labor_register = RegistrantVacancies::find()->select('id_labor')->where(['id_vacancies' => $id])->column();
        $id_labor = Labor::find()->where(['not', ['id' => $id_labor_register]])->andWhere(['account_verification' => 1])->orderBy('name')->all();
        $this->dropdown_data['id_labor'] = ArrayHelper::map($id_labor, 'id', 'name');

        if ($vacancies->publication == 1) {
            return $this->render('create', [
                'model'         => $model,
                'dropdown_data' => $this->dropdown_data,
                'url_prev'      => Url::previous()
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'Lowongan tidak sedang dipublikasi');
            return $this->redirect(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id_vacancies)]);
        }
    }

    /**
     * Updates an existing RegistrantVacancies model.
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
     * Deletes an existing RegistrantVacancies model.
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
     * Finds the RegistrantVacancies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistrantVacancies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistrantVacancies::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('registrant_vacancies');
        return $this->redirect(['index']);
    }
}
