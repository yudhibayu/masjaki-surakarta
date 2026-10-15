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
use yii\web\ServerErrorHttpException;
use app\modules\businessEntity\models\DeedOfIncorporation;
use app\modules\businessEntity\models\DeedOfIncorporationSearch;

/**
 * DeedOfIncorporationController implements the CRUD actions for DeedOfIncorporation model.
 */
class DeedOfIncorporationController extends Controller
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


        //monggo


    }

    /**
     * Lists all DeedOfIncorporation models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['deed_of_incorporation'];
            if (isset($this->sess['deed_of_incorporation']['page']))
                $_GET['page'] = $this->sess['deed_of_incorporation']['page'];
        } else {
            $this->sess['deed_of_incorporation'] = $params;
        }

        $searchModel = new DeedOfIncorporationSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeedOfIncorporation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous()
        ]);
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
        $filename = $model->deed_file . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }

    /**
     * Creates a new DeedOfIncorporation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model_deed_of_incorporation = new DeedOfIncorporation();
        $model_deed_of_incorporation->id_business_entity = $id;
        $model_deed_of_incorporation->date_of_deed = date('d-m-Y');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model_deed_of_incorporation->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model_deed_of_incorporation, 'deed_file');
                $model_deed_of_incorporation->file_data = $imageFile;
                if ($imageFile !== null) {
                    $model_deed_of_incorporation->file_extension = $imageFile->extension;
                    $model_deed_of_incorporation->file_size = $imageFile->size;
                    if (!$model_deed_of_incorporation->deed_file) {
                        $model_deed_of_incorporation->deed_file = $imageFile->name;
                    }

                    $uuid = Uuid::uuid6();
                    $filename = $uuid->toString() . '.' . strtolower($model_deed_of_incorporation->file_extension);
                    $folderpath = '@upload';
                    $model_deed_of_incorporation->file_name = $filename;
                    $model_deed_of_incorporation->file_path = $folderpath . '/' . $filename;

                    if (!$model_deed_of_incorporation->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                    if (!$imageFile->saveAs(Yii::getAlias($model_deed_of_incorporation->file_path))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                } else {
                    if (!$model_deed_of_incorporation->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model_deed_of_incorporation->deed_file]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model_deed_of_incorporation->id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();

        return $this->render('create', [
            'model_deed_of_incorporation'         => $model_deed_of_incorporation,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }
    /**
     * Updates an existing DeedOfIncorporation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model_deed_of_incorporation = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model_deed_of_incorporation->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model_deed_of_incorporation, 'deed_file');
                $model_deed_of_incorporation->file_data = $imageFile;
                if ($imageFile !== null) {
                    $allowedExtensions = ['pdf'];
                    $allowedMimeTypes = ['application/pdf'];
                    if (in_array($imageFile->extension, $allowedExtensions)) {
                        // && in_array($imageFile->type, $allowedMimeTypes)
                        if ($model_deed_of_incorporation->file_path) {
                            $existingFilePath = Yii::getAlias($model_deed_of_incorporation->file_path);
                            if (file_exists($existingFilePath)) {
                                unlink($existingFilePath);
                            }
                        }
                        $model_deed_of_incorporation->file_extension = $imageFile->extension;
                        $model_deed_of_incorporation->file_size = $imageFile->size;
                        if ($model_deed_of_incorporation->deed_file) {
                            $model_deed_of_incorporation->deed_file = $imageFile->name;
                        }
                        $uuid = Uuid::uuid6();
                        $filename = $uuid->toString() . '.' . strtolower($model_deed_of_incorporation->file_extension);
                        $folderpath = '@upload';
                        $model_deed_of_incorporation->file_name = $filename;
                        $model_deed_of_incorporation->file_path = $folderpath . '/' . $filename;
                    }
                    if (!$model_deed_of_incorporation->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                    if (!$imageFile->saveAs(Yii::getAlias($model_deed_of_incorporation->file_path))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                } else {
                    if (!$model_deed_of_incorporation->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model_deed_of_incorporation->deed_file]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model_deed_of_incorporation->id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();
        return $this->render('update', [
            'model_deed_of_incorporation'         => $model_deed_of_incorporation,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing DeedOfIncorporation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $parent = Yii::$app->hasher->decode($parent);

        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $parent = $model->id_business_entity;
        if (unlink(Yii::getAlias($model->file_path)) && $model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->deed_number]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->deed_number]));
        }

        return $this->redirect(['business-entity/view', 'id' => Yii::$app->hasher->encode($parent)]);
    }

    /**
     * Finds the DeedOfIncorporation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeedOfIncorporation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeedOfIncorporation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('deed_of_incorporation');
        return $this->redirect(['index']);
    }
}
