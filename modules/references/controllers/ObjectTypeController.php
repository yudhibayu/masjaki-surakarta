<?php

namespace app\modules\references\controllers;

use Yii;

use app\modules\references\models\ObjectType;
use app\modules\references\models\ObjectTypeSearch;
use Ramsey\Uuid\Uuid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * ObjectTypeController implements the CRUD actions for ObjectType model.
 */
class ObjectTypeController extends Controller
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
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
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
     * Lists all ObjectType models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['object_type'];
            if (isset($this->sess['object_type']['page']))
                $_GET['page'] = $this->sess['object_type']['page'];
        } else {
            $this->sess['object_type'] = $params;
        }

        $searchModel = new ObjectTypeSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ObjectType model.
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
     * Creates a new ObjectType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ObjectType();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'uploadimage');
            if ($file) {
                $fileextension = $file->extension;
                $filename = Uuid::uuid6()->toString() . '.' . $fileextension;
                $filepath = Yii::getAlias($model->folderroot) . DIRECTORY_SEPARATOR . $filename;
                // var_dump($filepath);exit;
                if (($model->icon != '') || ($model->icon != null)) {
                    if (($model->icon != 'marker-icon.png')) {
                        $oldfilepath = Yii::getAlias($model->folderroot) . DIRECTORY_SEPARATOR . $model->icon;
                        try {
                            unlink($oldfilepath);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }
                $model->icon = $filename;
                if ($file->saveAs($filepath) && $model->save()) {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                    return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                } else {
                    $model->validate();
                    // return $this->asJson($model->errors);
                    $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> has not been created.', ['name' => $model->name]));
                }
            } else {
                $model->icon = 'marker-icon.png';
                if ($model->save()) {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                    return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                }
            }
        }

        if (!$model->map_color) {
            $model->map_color = '#3388FF';
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing ObjectType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'uploadimage');
            if ($file) {
                $fileextension = $file->extension;
                $filename = Uuid::uuid6()->toString() . '.' . $fileextension;
                $filepath = Yii::getAlias($model->folderroot) . DIRECTORY_SEPARATOR . $filename;
                // var_dump($filepath);exit;
                if (($model->icon != '') || ($model->icon != null)) {
                    if (($model->icon != 'marker-icon.png')) {
                        $oldfilepath = Yii::getAlias($model->folderroot) . DIRECTORY_SEPARATOR . $model->icon;
                        try {
                            unlink($oldfilepath);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                }
                $model->icon = $filename;
                if ($file->saveAs($filepath) && $model->save()) {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                    return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                } else {
                    $model->validate();
                    // return $this->asJson($model->errors);
                    $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> has not been updated.', ['name' => $model->name]));
                }
            } else {
                if ($model->save()) {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                    return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                }
            }
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing ObjectType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ObjectType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObjectType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObjectType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('object_type');
        return $this->redirect(['index']);
    }
}
