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
use app\modules\businessEntity\models\BusinessPermit;
use app\modules\businessEntity\models\PhotoBusinessPermit;
use app\modules\businessEntity\models\PhotoBusinessPermitSearch;
use yii\web\ServerErrorHttpException;

/**
 * PhotoBusinessPermitController implements the CRUD actions for PhotoBusinessPermit model.
 */
class PhotoBusinessPermitController extends Controller
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

        // $id_business_permit = BusinessPermit::find()->orderBy('name')->all();
        // $this->dropdown_data['id_business_permit'] = ArrayHelper::map($id_business_permit, 'id', 'name');

        //monggo


    }

    /**
     * Lists all PhotoBusinessPermit models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['photo_business_permit'];
            if (isset($this->sess['photo_business_permit']['page']))
                $_GET['page'] = $this->sess['photo_business_permit']['page'];
        } else {
            $this->sess['photo_business_permit'] = $params;
        }

        $searchModel = new PhotoBusinessPermitSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'photoBusinessPermitModel' => $searchModel,
            'photoBusinessPermitDataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PhotoBusinessPermit model.
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
        $filename = $model->photo . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }

    /**
     * Creates a new PhotoBusinessPermit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new PhotoBusinessPermit();
        $model->id_business_permit = $id;

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
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->photo]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();

        return $this->render('create', [
            'model' => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev' => Url::previous()
        ]);
    }

    /**
     * Updates an existing PhotoBusinessPermit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model, 'photo');
                $model->file_data = $imageFile;
                if ($imageFile !== null) {
                    $allowedExtensions = ['pdf'];
                    $allowedMimeTypes = ['application/pdf'];
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
                $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been updated.', ['name' => $model->photo]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing PhotoBusinessPermit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $parent)
    {
        $parent = Yii::$app->hasher->decode($parent);
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        try {
            if (unlink(Yii::getAlias($model->file_path)) && $model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->photo]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->photo]));
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->redirect(['business-permit/view', 'id' => Yii::$app->hasher->encode($parent)]);
    }

    public function actionDownload($id)
    {
        $photoId = Yii::$app->hasher->decode($id);
        $model = PhotoBusinessPermit::findOne($photoId);

        $filePath = Yii::getAlias($model->file_path);
        $fileName = $model->photo;

        if (file_exists($filePath)) {
            Yii::$app->response->sendFile($filePath, $fileName, [
                'mimeType' => 'application/octet-stream',
                'inline' => false,
            ]);
        } else {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
    }

    /**
     * Finds the PhotoBusinessPermit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PhotoBusinessPermit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PhotoBusinessPermit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('photo_business_permit');
        return $this->redirect(['index']);
    }
}
