<?php

namespace app\modules\businessEntity\controllers;

use Yii;

use yii\helpers\Url;
use Ramsey\Uuid\Uuid;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\overrides\user\models\User;
use yii\web\ServerErrorHttpException;
use app\modules\businessEntity\models\Owner;
use app\modules\businessEntity\models\ChangeDeed;
use app\modules\businessEntity\models\OwnerSearch;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\businessEntity\models\Administrator;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\businessEntity\models\BusinessPermit;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\businessEntity\models\ChangeDeedSearch;
use app\modules\referencesMasjaki\models\FormOfBusiness;
use app\modules\businessEntity\models\AdministratorSearch;
use app\modules\businessEntity\models\DeedOfIncorporation;
use app\modules\businessEntity\models\PhotoBusinessPermit;
use app\modules\businessEntity\models\BusinessEntitySearch;
use app\modules\businessEntity\models\BusinessPermitSearch;
use app\modules\businessEntity\models\DeedOfIncorporationSearch;
use app\modules\businessEntity\models\ExperienceBusinessEntitySearch;
use app\modules\construction\models\PbjBusinessEntitySearch;

/**
 * BusinessEntityController implements the CRUD actions for BusinessEntity model.
 */
class BusinessEntityController extends Controller
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
                    'set-tab' => ['POST'],
                    'set-tab-akta' => ['POST'],
                    'set-tab-manajerial' => ['POST'],
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
                        'actions' => ['index', 'view', 'update', 'set-tab', 'set-tab-akta', 'set-tab-manajerial', 'fetch-file'],
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

        $form_of_business = FormOfBusiness::find()->orderBy('name')->all();
        $this->dropdown_data['form_of_business'] = ArrayHelper::map($form_of_business, 'id', 'name');
        $ref_negara = RefNegara::find()->orderBy('name')->all();
        $this->dropdown_data['ref_negara'] = ArrayHelper::map($ref_negara, 'id', 'name');
        //monggo


    }

    /**
     * Lists all BusinessEntity models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['business_entity'];
            if (isset($this->sess['business_entity']['page']))
                $_GET['page'] = $this->sess['business_entity']['page'];
        } else {
            $this->sess['business_entity'] = $params;
        }

        $searchModel = new BusinessEntitySearch();
        $dataProvider = $searchModel->search($params);
        $dataProviderVerified = $searchModel->search($params, $searchModel::VERIFICATION);
        $dataProviderUnverified = $searchModel->search($params, $searchModel::UNVERIFICATION);
        $dataProviderBlacklist = $searchModel->search($params, $searchModel::BLACKLIST);


        Url::remember();

        return $this->render('index', [
            'dataProviderUnverified' => $dataProviderUnverified,
            'dataProviderVerified' => $dataProviderVerified,
            'dataProviderBlacklist' => $dataProviderBlacklist,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BusinessEntity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $params = Yii::$app->request->queryParams;

        $businessPermitModel = new BusinessPermitSearch();
        $businessPermitDataProvider = $businessPermitModel->search($params, $id);

        $administratorModel = new AdministratorSearch();
        $administratorDataProvider = $administratorModel->search($params, $id);

        $changeDeedModel = new ChangeDeedSearch();
        $changeDeedDataProvider = $changeDeedModel->search($params, $id);

        $deedOfIncorporationModel = new DeedOfIncorporationSearch();
        $deedOfIncorporationDataProvider = $deedOfIncorporationModel->search($params, $id);

        $ownerModel = new OwnerSearch();
        $ownerDataProvider = $ownerModel->search($params, $id);

        $experienceModel = new ExperienceBusinessEntitySearch();
        $experienceDataProvider = $experienceModel->search($params, $id);

        $pbjBusinessEntityModel = new PbjBusinessEntitySearch();
        $pbjBusinessEntityDataProvider = $pbjBusinessEntityModel->search($params, null, $id);

        if (!isset($this->sess['view_tab']['nav'])) {
            $this->sess->set('view_tab', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                    2 => '',
                    3 => '',
                    4 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                    2 => 'fade',
                    3 => 'fade',
                    4 => 'fade',
                ],
            ]);
        }

        if (!isset($this->sess['view_tab_akta']['nav'])) {
            $this->sess->set('view_tab_akta', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                ],
            ]);
        }

        if (!isset($this->sess['view_tab_manajerial']['nav'])) {
            $this->sess->set('view_tab_manajerial', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                ],
            ]);
        }

        $view_tab = $this->sess['view_tab'];
        $view_tab_akta = $this->sess['view_tab_akta'];
        $view_tab_manajerial = $this->sess['view_tab_manajerial'];

        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),
            'businessPermitModel' => $businessPermitModel,
            'businessPermitDataProvider' => $businessPermitDataProvider,
            'administratorModel' => $administratorModel,
            'administratorDataProvider' => $administratorDataProvider,
            'changeDeedModel' => $changeDeedModel,
            'changeDeedDataProvider' => $changeDeedDataProvider,
            'deedOfIncorporationModel' => $deedOfIncorporationModel,
            'deedOfIncorporationDataProvider' => $deedOfIncorporationDataProvider,
            'ownerModel' => $ownerModel,
            'ownerDataProvider' => $ownerDataProvider,
            'experienceModel' => $experienceModel,
            'experienceDataProvider' => $experienceDataProvider,
            'pbjBusinessEntityModel' => $pbjBusinessEntityModel,
            'pbjBusinessEntityDataProvider' => $pbjBusinessEntityDataProvider,
            'view_tab' => $view_tab,
            'view_tab_akta' => $view_tab_akta,
            'view_tab_manajerial' => $view_tab_manajerial
        ]);
    }

    public function actionSetTab()
    {
        if (!isset($this->sess['view_tab']['nav'])) {
            $this->sess->set('view_tab', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                    2 => '',
                    3 => '',
                    4 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                    2 => 'fade',
                    3 => 'fade',
                    4 => 'fade',
                ],
            ]);
        }
        $navs = $this->sess['view_tab']['nav'];
        $tabs = $this->sess['view_tab']['tab'];
        $tabid = (int) Yii::$app->request->post('tab', 0);
        if ($tabid < 0 || $tabid > 4) {
            $tabid = 0;
        }
        for ($i = 0; $i < 5; $i++) {
            if ($i == $tabid) {
                $navs[$i] = 'active';
                $tabs[$i] = 'fade show active';
            } else {
                $navs[$i] = '';
                $tabs[$i] = 'fade';
            }
        }
        $this->sess->set('view_tab', [
            'nav' => $navs,
            'tab' => $tabs,
        ]);
        // $this->sess['monitoring_view_tab']['nav'] = $navs;
        // $this->sess['monitoring_view_tab']['tab'] = $tabs;

        return $this->asJson(['status' => 'ok']);
    }

    public function actionSetTabAkta()
    {
        if (!isset($this->sess['view_tab_akta']['nav'])) {
            $this->sess->set('view_tab_akta', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                ],
            ]);
        }
        $navs = $this->sess['view_tab_akta']['nav'];
        $tabs = $this->sess['view_tab_akta']['tab'];
        $tabid = (int) Yii::$app->request->post('tab', 0);
        if ($tabid < 0 || $tabid > 1) {
            $tabid = 0;
        }
        for ($i = 0; $i < 2; $i++) {
            if ($i == $tabid) {
                $navs[$i] = 'active';
                $tabs[$i] = 'fade show active';
            } else {
                $navs[$i] = '';
                $tabs[$i] = 'fade';
            }
        }
        $this->sess->set('view_tab_akta', [
            'nav' => $navs,
            'tab' => $tabs,
        ]);
        // $this->sess['monitoring_view_tab']['nav'] = $navs;
        // $this->sess['monitoring_view_tab']['tab'] = $tabs;

        return $this->asJson(['status' => 'ok']);
    }

    public function actionSetTabManajerial()
    {
        if (!isset($this->sess['view_tab_manajerial']['nav'])) {
            $this->sess->set('view_tab_manajerial', [
                'nav' => [
                    0 => 'active',
                    1 => '',
                ],
                'tab' => [
                    0 => 'fade show active',
                    1 => 'fade',
                ],
            ]);
        }
        $navs = $this->sess['view_tab_manajerial']['nav'];
        $tabs = $this->sess['view_tab_manajerial']['tab'];
        $tabid = (int) Yii::$app->request->post('tab', 0);
        if ($tabid < 0 || $tabid > 1) {
            $tabid = 0;
        }
        for ($i = 0; $i < 2; $i++) {
            if ($i == $tabid) {
                $navs[$i] = 'active';
                $tabs[$i] = 'fade show active';
            } else {
                $navs[$i] = '';
                $tabs[$i] = 'fade';
            }
        }
        $this->sess->set('view_tab_manajerial', [
            'nav' => $navs,
            'tab' => $tabs,
        ]);
        // $this->sess['monitoring_view_tab']['nav'] = $navs;
        // $this->sess['monitoring_view_tab']['tab'] = $tabs;

        return $this->asJson(['status' => 'ok']);
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
        $user->id_business_entity = $model->id;
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
                $roleObj = $auth->getRole('Badan Usaha');
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
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been verified.', ['name' => $model->company_name]));
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
                $item = $auth->getRole('Badan Usaha');
                $item = $item ?: $auth->getPermission('Badan Usaha');
                $auth->revoke($item, $user->getId());
            }
            if ($model->account_verification) {
                $model->account_verification = $model::UNVERIFICATION;
                if (!$model->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been unverified.', ['name' => $model->company_name]));
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
     * Fetch a file of PhotoBusinessPermit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFetchFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $filepath = Yii::getAlias($model->file_path);
        $filename = $model->photo . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }
    /**
     * Creates a new BusinessEntity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BusinessEntity();
        $model->id_country = 100;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model, 'photo');
                $model->file_data = $imageFile;
                if ($imageFile !== null) {
                    $model->file_extension = $imageFile->extension;
                    $model->file_size = $imageFile->size;
                    if (!$model->photo) {
                        $model->photo = $imageFile->name;
                    }
                    $uuid = Uuid::uuid6();
                    $filename = $uuid->toString() . '.' . strtolower($model->file_extension);
                    $folderpath = '@upload';
                    $model->file_name = $filename;
                    $model->file_path = $folderpath . '/' . $filename;
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                    if (!$imageFile->saveAs(Yii::getAlias($model->file_path))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                } else {
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                }
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been created. Wait for account verification, you will be notified via email', ['name' => $model->company_name]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            // throw $e;
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous(),
        ]);
    }
    /**
     * Updates an existing BusinessEntity model.
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
                $imageFile = UploadedFile::getInstance($model, 'photo');
                $model->file_data = $imageFile;
                if ($imageFile !== null) {
                    $allowedExtensions = ['png', 'jpg', 'jpeg'];
                    $allowedMimeTypes = ['image/*'];
                    if (in_array($imageFile->extension, $allowedExtensions)) {
                        // && in_array($imageFile->type, $allowedMimeTypes)
                        if ($model->file_path) {
                            $existingFilePath = Yii::getAlias($model->file_path);
                            if (file_exists($existingFilePath)) {
                                unlink($existingFilePath);
                            }
                        }
                        $model->file_extension = $imageFile->extension;
                        $model->file_size = $imageFile->size;
                        if ($model->photo) {
                            $model->photo = $imageFile->name;
                        }
                        $uuid = Uuid::uuid6();
                        $filename = $uuid->toString() . '.' . strtolower($model->file_extension);
                        $folderpath = '@upload';
                        $model->file_name = $filename;
                        $model->file_path = $folderpath . '/' . $filename;
                    }
                    if ($model->account_verification !== $model::UNVERIFICATION && ($model->username !== $oldUsername || $model->email !== $oldEmail)) {
                        throw new ServerErrorHttpException('Gagal menyimpan data! (Username/Email), Akun sudah terverifikasi');
                    }
                    if ($model->account_verification !== $model::UNVERIFICATION && !empty($model->password)) {
                        throw new ServerErrorHttpException('Gagal menyimpan data! (Password), Akun sudah terverifikasi');
                    }
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                    if (!$imageFile->saveAs(Yii::getAlias($model->file_path))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                } else {
                    if ($model->account_verification !== $model::UNVERIFICATION && ($model->username !== $oldUsername || $model->email !== $oldEmail)) {
                        throw new ServerErrorHttpException('Gagal menyimpan data! (Username/Email), Akun sudah terverifikasi');
                    }
                    if ($model->account_verification !== $model::UNVERIFICATION && !empty($model->password)) {
                        throw new ServerErrorHttpException('Gagal menyimpan data! (Password), Akun sudah terverifikasi');
                    }
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been updated.', ['name' => $model->company_name]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
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
     * Deletes an existing BusinessEntity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $administrator = Administrator::find()->where(['id_business_entity' => $id])->all();
        $businessPermit = BusinessPermit::find()->where(['id_business_entity' => $id])->all();
        $changeDeed = ChangeDeed::find()->where(['id_business_entity' => $id])->all();
        $deedOfIncorporation = DeedOfIncorporation::find()->where(['id_business_entity' => $id])->all();
        $owner = Owner::find()->where(['id_business_entity' => $id])->all();

        $model = $this->findModel($id);

        if (!empty($administrator || $businessPermit || $changeDeed || $deedOfIncorporation || $owner)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Data cannot be deleted because there is child data inside.'));
        } else {
            if ($model->delete()) {
                if (file_exists(Yii::getAlias($model->file_path))) {
                    unlink(Yii::getAlias($model->file_path));
                }
                $user = User::findOne(['username' => $model->username]);
                if ($user !== null) {
                    if (!$user->delete()) {
                        throw new ServerErrorHttpException('Failed to delete user record.');
                    }
                }
                $auth = \Yii::$app->authManager;
                $item = $auth->getRole('Badan Usaha');
                $item = $item ?: $auth->getPermission('Badan Usaha');
                $auth->revoke($item, $user->getId());
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->company_name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->company_name]));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the BusinessEntity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessEntity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('business_entity');
        return $this->redirect(['index']);
    }
}
