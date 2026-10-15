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
use app\modules\construction\models\ConstructionSafety;
use app\modules\construction\models\ConstructionSafetySearch;

/**
 * ConstructionSafetyController implements the CRUD actions for ConstructionSafety model.
 */
class ConstructionSafetyController extends Controller
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
                        'actions' => ['fetch-file', 'view'],
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
     * Lists all ConstructionSafety models.
     * @return mixed
     */
    public function actionIndex($id)
    {

        $id = Yii::$app->hasher->decode($id);
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['construction_safety'];
            if (isset($this->sess['construction_safety']['page']))
                $_GET['page'] = $this->sess['construction_safety']['page'];
        } else {
            $this->sess['construction_safety'] = $params;
        }

        $searchModel = new ConstructionSafetySearch();
        $dataProvider = $searchModel->search($params, $id);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConstructionSafety model.
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
        $filename = $model->report_file . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }

    /**
     * Creates a new ConstructionSafety model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new ConstructionSafety();
        $model->id_construction_work = $id;
        $model->date = date('d-m-Y');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model, 'report_file');
                $model->file_data = $imageFile;
                if ($imageFile !== null) {
                    $model->file_extension = $imageFile->extension;
                    $model->file_size = $imageFile->size;
                    if (!$model->report_file) {
                        $model->report_file = $imageFile->name;
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

                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->report_file]));
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
     * Updates an existing ConstructionSafety model.
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
                $imageFile = UploadedFile::getInstance($model, 'report_file');
                $model->file_data = $imageFile;
                if ($imageFile !== null) {
                    $allowedExtensions = ['pdf', 'jpg', 'png', 'jpeg'];
                    $allowedMimeTypes = ['appliaction/pdf', 'image/*'];
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
                $this->sess->setFlash('success', Yii::t('app', 'Record <strong>{name}</strong> has been updated.', ['name' => $model->report_file]));
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
     * Deletes an existing ConstructionSafety model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $parent = $model->id_construction_work;

        if (unlink(Yii::getAlias($model->file_path)) && $model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->report_file]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->report_file]));
        }

        return $this->redirect(['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($parent)]);
    }

    /**
     * Finds the ConstructionSafety model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConstructionSafety the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConstructionSafety::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('construction_safety');
        return $this->redirect(['index']);
    }
}
