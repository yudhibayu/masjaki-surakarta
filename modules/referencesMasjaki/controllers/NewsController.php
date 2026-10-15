<?php

namespace app\modules\referencesMasjaki\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\modules\referencesMasjaki\models\News;
use app\modules\referencesMasjaki\models\NewsSearch;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['news'];
            if (isset($this->sess['news']['page']))
                $_GET['page'] = $this->sess['news']['page'];
        } else {
            $this->sess['news'] = $params;
        }

        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        // $modell = News::findOne($id);
        // Yii::$app->hitCounter->hit('news', $modell->id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        $model->date = date('d-m-Y');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {

                $imageFile = UploadedFile::getInstance($model, 'thumbnail');
                $model->file_data = $imageFile;
                // var_dump($imageFile);
                // exit();
                if ($imageFile !== null) {
                    $imageName = 'news_' . Yii::$app->security->generateRandomString(10) . '.' . $imageFile->extension;
                    $model->thumbnail = $imageName;
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                    if (!$imageFile->saveAs(Yii::getAlias('@webroot/photo/') . $imageName)) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                } else {
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->title]));
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
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $oldImage = $model->thumbnail;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $imageFile = UploadedFile::getInstance($model, 'thumbnail');
                $model->file_data = $imageFile;
                if ($imageFile !== null) {
                    $allowedExtensions = ['jpg', 'png', 'jpeg'];
                    $allowedMimeTypes = ['image/*'];
                    if (in_array($imageFile->extension, $allowedExtensions)) {
                        // && in_array($imageFile->type, $allowedMimeTypes)
                        if ($oldImage) {
                            $existingFilePath = Yii::getAlias('@webroot/photo/') . $oldImage;

                            if (file_exists($existingFilePath)) {
                                unlink($existingFilePath);
                            }
                        }
                        $imageName = 'news_' . Yii::$app->security->generateRandomString(10) . '.' . $imageFile->extension;
                        $model->thumbnail = $imageName;
                    }
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                    if (!$imageFile->saveAs(Yii::getAlias('@webroot/photo/') . $imageName)) {
                        throw new ServerErrorHttpException('Gagal menyimpan file!');
                    }
                } else {
                    $model->thumbnail = $oldImage;
                    if (!$model->save()) {
                        throw new ServerErrorHttpException('Gagal menyimpan data!');
                    }
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->title]));
                $transaction->commit();
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
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
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $oldImage = $model->thumbnail;
        if ($oldImage && file_exists(Yii::getAlias('@webroot/photo/') . $oldImage)) {
            unlink(Yii::getAlias('@webroot/photo/') . $oldImage);
        }

        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->title]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->title]));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('news');
        return $this->redirect(['index']);
    }
}
