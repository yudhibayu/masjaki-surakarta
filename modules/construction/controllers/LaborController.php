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
use app\overrides\user\models\User;
use yii\web\ServerErrorHttpException;
use app\modules\construction\models\Labor;
use app\modules\construction\models\LaborSearch;
use app\modules\referencesMasjaki\models\Education;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\construction\models\ConstructionWork;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\construction\models\RegistrantTraining;
use app\modules\construction\models\RegistrantVacancies;
use app\modules\construction\models\ConstructionVacancies;
use app\modules\construction\models\RegistrantTrainingSearch;
use app\modules\construction\models\RegistrantVacanciesSearch;

/**
 * LaborController implements the CRUD actions for Labor model.
 */
class LaborController extends Controller
{
    private $dropdown_data;
    private $sess;
    public $password;

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
                        'roles' => ['Team'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update'],
                        'roles' => ['Tenaga Kerja'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['Badan Usaha'],
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
        $constructionVacancies = ConstructionVacancies::find()->orderBy('name')->all();
        $this->dropdown_data['constructionVacancies'] = ArrayHelper::map($constructionVacancies, 'id', 'name');
        $ref_negara = RefNegara::find()->orderBy('name')->all();
        $this->dropdown_data['ref_negara'] = ArrayHelper::map($ref_negara, 'id', 'name');
        //monggo


    }

    /**
     * Lists all Labor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['labor'];
            if (isset($this->sess['labor']['page']))
                $_GET['page'] = $this->sess['labor']['page'];
        } else {
            $this->sess['labor'] = $params;
        }

        $searchModel = new LaborSearch();
        $dataProvider = $searchModel->search($params);
        $dataProviderVerified = $searchModel->search($params, $searchModel::VERIFICATION);
        $dataProviderUnverified = $searchModel->search($params, $searchModel::UNVERIFICATION);
        $dataProviderBlacklist = $searchModel->search($params, $searchModel::BLACKLIST);
        $dataProviderMenganggur = $searchModel->search($params, $searchModel::VERIFICATION, true);
        $dataProviderMelamar = $searchModel->search($params, $searchModel::VERIFICATION, null, RegistrantVacancies::UNSELECTED);
        $dataProviderBekerja = $searchModel->search($params, $searchModel::VERIFICATION, null, RegistrantVacancies::SELECTED);
        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderUnverified' => $dataProviderUnverified,
            'dataProviderVerified' => $dataProviderVerified,
            'dataProviderBlacklist' => $dataProviderBlacklist,
            'dataProviderMenganggur' => $dataProviderMenganggur,
            'dataProviderMelamar' => $dataProviderMelamar,
            'dataProviderBekerja' => $dataProviderBekerja,
        ]);
    }

    // public function actionSelect($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     $model = $this->findModel($id);
    //     $status = Labor::findOne(['id' => $id]);
    //     // return $this->asJson($status);

    //     $user = new ConstructionWork();
    //     $user->winner = $model->id;
    //     $user->confirm();
    //     if (!$user->save(false)) {
    //         throw new ServerErrorHttpException('Cannot save data');
    //     }
    //     if (!$status->account_verification) {
    //         $status->account_verification = 1;
    //         if (!$status->save(false)) {
    //             throw new ServerErrorHttpException('Cannot save data');
    //         }
    //     }

    //     $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added to Construction Work.', ['name' => $model->username]));
    //     return $this->redirect(['index']);
    // }

    /**
     * Displays a single Labor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;
        $searchModelVacancies = new RegistrantVacanciesSearch();
        $dataProviderVacancies = $searchModelVacancies->search($params, null, null, null, $id);
        $searchModelTraining = new RegistrantTrainingSearch();
        $dataProviderTraining = $searchModelTraining->search($params, null, null, null, $id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'searchModelVacancies' => $searchModelVacancies,
            'dataProviderVacancies' => $dataProviderVacancies,
            'searchModelTraining' => $searchModelTraining,
            'dataProviderTraining' => $dataProviderTraining,
        ]);
    }

    /**
     * Creates a new Labor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Labor();
        $model->id_country = 100;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been created. Wait for account verification, you will be notified via email', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save record.'));
            }
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }


    public function actionVerifikasi($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $existingUser = User::find()->where(['username' => $model->username])
            ->orWhere(['email' => $model->email])
            ->exists();


        $user = new User();
        $user->username = $model->username;
        $user->password_hash = $model->password_hash;
        $user->email = $model->email;
        $user->id_labor = $model->id;
        $user->confirm();
        if ($model->account_verification == $model::UNVERIFICATION) {
            if (!$model->password_hash) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Password Tidak ada. Silahkan lengkapi Password terlebih dahulu'));
                return $this->redirect(['index']);
            } elseif ($existingUser) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Verification failed. Username or email already exists'));
                return $this->redirect(['index']);
            } else {
                if (!$user->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
                if (!$model->account_verification) {
                    $model->account_verification = $model::VERIFICATION;
                    if (!$model->save(false)) {
                        throw new ServerErrorHttpException('Cannot save data');
                    }
                }
                $auth = \Yii::$app->authManager;
                $roleObj = $auth->getRole('Tenaga Kerja');
                $auth->assign($roleObj, $user->getId());

                $send = Yii::$app->mailer->compose('EmailNotification', [
                    'model' => $user,
                    'params' => Yii::$app->params,
                ])
                    // ->setFrom('wijilestari@depod.co.id')
                    ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['subjectEmail']])
                    ->setTo($user->email)
                    ->setSubject(Yii::t('app', 'Confirm Registration Data'))
                    ->send();
                if ($send) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Email send'));
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been verified.', ['name' => $model->username]));
            }
        } else {
            if ($model->account_verification == $model::VERIFICATION) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Verification failed. User has been verified'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Verification failed. User has been blocked'));
            }
        }
        return $this->redirect(['index']);
    }

    public function actionUnverifikasi($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $user = User::findOne(['username' => $model->username]);
        if ($model->account_verification == $model::VERIFICATION) {
            if ($user !== null) {
                if (!$user->delete()) {
                    throw new ServerErrorHttpException('Failed to delete user record.');
                }
                $auth = \Yii::$app->authManager;
                $item = $auth->getRole('Tenaga Kerja');
                $item = $item ?: $auth->getPermission('Tenaga Kerja');
                $auth->revoke($item, $user->getId());
            }

            if ($model->account_verification) {
                $model->account_verification = $model::UNVERIFICATION;
                if (!$model->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been unverified.', ['name' => $model->username]));
        } else {
            if ($model->account_verification == $model::UNVERIFICATION) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Unverification failed. User not yet verified'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Unverification failed. User has been blocked'));
            }
        }
        return $this->redirect(['index']);
    }


    public function actionBlacklist($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $user = User::findOne(['username' => $model->username]);
        if ($user !== null) {
            if (!$user->delete()) {
                throw new ServerErrorHttpException('Failed to delete user record.');
            }
            $auth = \Yii::$app->authManager;
            $item = $auth->getRole('Badan Usaha');
            $item = $item ?: $auth->getPermission('Badan Usaha');
            $auth->revoke($item, $user->getId());
        }

        $model->account_verification = $model::BLACKLIST;
        if (!$model->save(false)) {
            throw new ServerErrorHttpException('Cannot save data');
        }

        $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been blocked.', ['name' => $model->username]));
        return $this->redirect(['index']);
    }

    public function actionUnblacklist($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if ($model->account_verification == $model::BLACKLIST) {
            if ($model->account_verification) {
                $model->account_verification = $model::UNVERIFICATION;
                if (!$model->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been unblocked.', ['name' => $model->username]));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Unblacklist failed. User never blocked'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Labor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $oldUsername = $model->username;
        $oldEmail = $model->email;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->account_verification !== $model::UNVERIFICATION && ($model->username !== $oldUsername || $model->email !== $oldEmail)) {
                    throw new ServerErrorHttpException('Gagal menyimpan data! (Username/Email), Akun sudah terverifikasi');
                }
                if ($model->account_verification !== $model::UNVERIFICATION && !empty($model->password)) {
                    throw new ServerErrorHttpException('Gagal menyimpan data! (Password), Akun sudah terverifikasi');
                }
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Gagal menyimpan data!');
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            // throw $e;
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        $this->populate_dropdown();

        if ($model->account_verification !== $model::BLACKLIST) {
            return $this->render('update', [
                'model'         => $model,
                'dropdown_data' => $this->dropdown_data,
                'url_prev'      => Url::previous()
            ]);
        } else {
            // if ($model->account_verification == 1) {
            //     Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed. User has been verified'));
            // } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Update failed. User has been blocked'));
            // }
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing Labor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $registrant_training = RegistrantTraining::find()->where(['id_labor' => $id])->all();
        $registrant_vacancies = RegistrantVacancies::find()->where(['id_labor' => $id])->all();
        $model = $this->findModel($id);
        if (!empty($registrant_training || $registrant_vacancies)) {
            Yii::$app->session->setFlash('error', 'Data tidak dapat dihapus karena terdapat child data didalamnya');
        } else {
            if ($model->delete()) {
                $user = User::findOne(['username' => $model->username]);
                if ($user !== null) {
                    if (!$user->delete()) {
                        throw new ServerErrorHttpException('Failed to delete user record.');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Labor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Labor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Labor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('labor');
        return $this->redirect(['index']);
    }
}