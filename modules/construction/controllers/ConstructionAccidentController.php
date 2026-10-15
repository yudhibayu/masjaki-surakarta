<?php

namespace app\modules\construction\controllers;

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
use app\modules\construction\models\ConstructionWork;
use app\modules\construction\models\ConstructionAccident;
use app\modules\construction\models\ConstructionAccidentFile;
use app\modules\construction\models\ConstructionAccidentFileSearch;
use app\modules\construction\models\ConstructionAccidentSearch;

/**
 * ConstructionAccidentController implements the CRUD actions for ConstructionAccident model.
 */
class ConstructionAccidentController extends Controller
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
                        'actions' => ['fetch-file-after', 'fetch-file-before', 'view'],
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

        // $id_construction_work = ConstructionWork::find()->orderBy('name')->all();
        // $this->dropdown_data['id_construction_work'] = ArrayHelper::map($id_construction_work, 'id', 'name');

        //monggo


    }

    /**
     * Lists all ConstructionAccident models.
     * @return mixed
     */
    // public function actionIndex($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);
    //     $params = Yii::$app->request->queryParams;

    //     if (count($params) <= 1) {
    //         $params = $this->sess['construction_accident'];
    //         if (isset($this->sess['construction_accident']['page']))
    //             $_GET['page'] = $this->sess['construction_accident']['page'];
    //     } else {
    //         $this->sess['construction_accident'] = $params;
    //     }

    //     $searchModel = new ConstructionAccidentSearch();
    //     $dataProvider = $searchModel->search($params, $id);

    //     Url::remember();

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['construction_accident'];
            if (isset($this->sess['construction_accident']['page']))
                $_GET['page'] = $this->sess['construction_accident']['page'];
        } else {
            $this->sess['construction_accident'] = $params;
        }

        $searchModel = new ConstructionAccidentSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single ConstructionAccident model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $params = Yii::$app->request->queryParams;

        $accidentFileModel = new ConstructionAccidentFileSearch();
        $accidentFileDataProvider = $accidentFileModel->search($params, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'accidentFileModel' => $accidentFileModel,
            'accidentFileDataProvider' => $accidentFileDataProvider,
        ]);
    }

    /**
     * Fetch a file of PhotoBusinessPermit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFetchFileBefore($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $filepath = Yii::getAlias($model->file_path_before);
        $filename = $model->photo_before_repair . '.' . $model->file_extension_before;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }
    /**
     * Fetch a file of PhotoBusinessPermit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFetchFileAfter($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $filepath = Yii::getAlias($model->file_path_after);
        $filename = $model->photo_after_repair . '.' . $model->file_extension_after;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }
    /**
     * Creates a new ConstructionAccident model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new ConstructionAccident();
        $model->id_construction_work = $id;
        // $model->date = date('d-m-Y');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $imageFileBefore = UploadedFile::getInstance($model, 'photo_before_repair');
                $model->file_data_before = $imageFileBefore;
                if ($imageFileBefore !== null) {
                    $model->file_extension_before = $imageFileBefore->extension;
                    $model->file_size_before = $imageFileBefore->size;
                    if (!$model->photo_before_repair) {
                        $model->photo_before_repair = $imageFileBefore->name;
                    }
                    $uuidBefore = Uuid::uuid6();
                    $filenameBefore = $uuidBefore->toString() . '.' . strtolower($model->file_extension_before);
                    $folderpathBefore = '@upload';
                    $model->file_name_before = $filenameBefore;
                    $model->file_path_before = $folderpathBefore . '/' . $filenameBefore;
                }
                $imageFileAfter = UploadedFile::getInstance($model, 'photo_after_repair');
                $model->file_data_after = $imageFileAfter;
                if ($imageFileAfter !== null) {
                    $model->file_extension_after = $imageFileAfter->extension;
                    $model->file_size_after = $imageFileAfter->size;
                    if (!$model->photo_after_repair) {
                        $model->photo_after_repair = $imageFileAfter->name;
                    }
                    $uuidAfter = Uuid::uuid6();
                    $filenameAfter = $uuidAfter->toString() . '.' . strtolower($model->file_extension_after);
                    $folderpathAfter = '@upload';
                    $model->file_name_after = $filenameAfter;
                    $model->file_path_after = $folderpathAfter . '/' . $filenameAfter;
                }
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Gagal menyimpan data!');
                }
                if ($imageFileBefore !== null) {
                    if (!$imageFileBefore->saveAs(Yii::getAlias($model->file_path_before))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                }
                if ($imageFileAfter !== null) {
                    if (!$imageFileAfter->saveAs(Yii::getAlias($model->file_path_after))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record has been created.'));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing ConstructionAccident model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $old_file_path_before = $model->file_path_before;
        $old_file_path_after = $model->file_path_after;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $imageFileBefore = UploadedFile::getInstance($model, 'photo_before_repair');
                $model->file_data_before = $imageFileBefore;
                if ($imageFileBefore !== null) {
                    $allowedExtensions = ['pdf', 'jpg', 'png', 'jpeg'];
                    $allowedMimeTypes = ['appliaction/pdf', 'image/*'];
                    if (in_array($imageFileBefore->extension, $allowedExtensions)) {
                        // && in_array($imageFileBefore->type, $allowedMimeTypes)
                        if ($model->file_path_before) {
                            $existingFilePath = Yii::getAlias($model->file_path_before);
                            if (file_exists($existingFilePath)) {
                                unlink($existingFilePath);
                            }
                        }
                        $model->file_extension_before = $imageFileBefore->extension;
                        $model->file_size_before = $imageFileBefore->size;
                        $uuidBefore = Uuid::uuid6();
                        $filenameBefore = $uuidBefore->toString() . '.' . strtolower($model->file_extension_before);
                        $folderpathBefore = '@upload';
                        $model->file_name_before = $filenameBefore;
                        $model->file_path_before = $folderpathBefore . '/' . $filenameBefore;
                    }
                }

                $imageFileAfter = UploadedFile::getInstance($model, 'photo_after_repair');
                $model->file_data_after = $imageFileAfter;
                if ($imageFileAfter !== null) {
                    $allowedExtensions = ['pdf', 'jpg', 'png', 'jpeg'];
                    $allowedMimeTypes = ['appliaction/pdf', 'image/*'];
                    if (in_array($imageFileAfter->extension, $allowedExtensions)) {
                        // && in_array($imageFileAfter->type, $allowedMimeTypes)
                        if ($model->file_path_after) {
                            $existingFilePath = Yii::getAlias($model->file_path_after);
                            if (file_exists($existingFilePath)) {
                                unlink($existingFilePath);
                            }
                        }
                        $model->file_extension_after = $imageFileAfter->extension;
                        $model->file_size_after = $imageFileAfter->size;
                        $uuidAfter = Uuid::uuid6();
                        $filenameAfter = $uuidAfter->toString() . '.' . strtolower($model->file_extension_after);
                        $folderpathAfter = '@upload';
                        $model->file_name_after = $filenameAfter;
                        $model->file_path_after = $folderpathAfter . '/' . $filenameAfter;
                    }
                }
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Gagal menyimpan data!');
                }
                if ($imageFileBefore !== null) {
                    if (!$imageFileBefore->saveAs(Yii::getAlias($model->file_path_before))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                }
                if ($imageFileAfter !== null) {
                    if (!$imageFileAfter->saveAs(Yii::getAlias($model->file_path_after))) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record has been updated.'));
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
     * Deletes an existing ConstructionAccident model.
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

        $parent = $model->id_construction_work;

        $fileacc = ConstructionAccidentFile::find()->where(['construction_accident_id' => $id])->all();
        // if (unlink(Yii::getAlias($model->file_path_before)) && unlink(Yii::getAlias($model->file_path_after)) && $model->delete()) {
        //     $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->place]));
        // } else {
        if ($fileacc) {
            foreach ($fileacc as $realizationitem) {
                $file_lengkap = Yii::getAlias($realizationitem['file_path']);
                $tes = file_exists($file_lengkap);
                if ($tes) {
                    unlink($file_lengkap);
                }
                $realizationitem->delete();
            }
        }

        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record successfully deleted.'));
        }

        return $this->redirect(['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($parent)]);
    }

    /**
     * Finds the ConstructionAccident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConstructionAccident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConstructionAccident::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('construction_accident');
        return $this->redirect(['index']);
    }
}