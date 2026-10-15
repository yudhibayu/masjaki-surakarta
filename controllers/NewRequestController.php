<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;

use Ramsey\Uuid\Uuid;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\components\Theming;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\TblAccountRequest;
use yii\web\NotFoundHttpException;
use app\overrides\user\models\User;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use app\overrides\user\models\Profile;
use app\models\TblAccountRequestSearch;
use app\modules\construction\models\Labor;
use app\overrides\user\models\AuthAssignment;
use app\modules\referencesMasjaki\models\Education;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\referencesMasjaki\models\FormOfBusiness;
use app\modules\construction\models\ConstructionVacancies;

class NewRequestController extends Controller
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
                    'confirm' => ['POST'],
                    'reject' => ['POST'],
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
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['select', 'labor', 'business-entity'],
                        'roles' => ['?'],
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
        Theming::setThemes(Yii::$app->params['frontEndTheme']);

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

        $education = Education::find()->orderBy('name')->all();
        $this->dropdown_data['education'] = ArrayHelper::map($education, 'id', 'name');
        $constructionVacancies = ConstructionVacancies::find()->orderBy('name')->all();
        $this->dropdown_data['constructionVacancies'] = ArrayHelper::map($constructionVacancies, 'id', 'name');
        $ref_negara = RefNegara::find()->orderBy('name')->all();
        $this->dropdown_data['ref_negara'] = ArrayHelper::map($ref_negara, 'id', 'name');
        //monggo


    }

    public function actionSelect()
    {
        return $this->render('select');
    }

    public function actionLabor()
    {
        $model = new Labor();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been created. Wait for account verification, you will be notified via email', ['name' => $model->name]));
                return $this->redirect(['select']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to save record.'));
            }
        }

        $this->populate_dropdown();

        return $this->render('labor', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }
    public function actionBusinessEntity()
    {
        $model = new BusinessEntity();
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
                return $this->redirect(['select']);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            // throw $e;
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();

        return $this->render('business-entity', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous(),
        ]);
    }

    /**
     * Finds the TblAccountRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAccountRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblAccountRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('tbl_account_request');
        return $this->redirect(['index']);
    }
}
