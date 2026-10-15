<?php

namespace app\modules\construction\controllers;

use app\modules\construction\models\ConstructionAccident;
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
use app\modules\construction\models\ConstructionAccidentFile;
use app\modules\construction\models\ConstructionAccidentFileSearch;

/**
 * ConstructionAccidentFileController implements the CRUD actions for ConstructionAccidentFile model.
 */
class ConstructionAccidentFileController extends Controller
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

    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['construction_accident_file'];
            if (isset($this->sess['construction_accident_file']['page']))
                $_GET['page'] = $this->sess['construction_accident_file']['page'];
        } else {
            $this->sess['construction_accident_file'] = $params;
        }

        $searchModel = new ConstructionAccidentFileSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single ConstructionAccidentFile model.
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
     * Creates a new ConstructionAccidentFile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pnid = null)
    {
        $model = new ConstructionAccidentFile();
        $pnid = Yii::$app->hasher->decode($pnid);
        $pnote = ConstructionAccident::findOne(['id' => $pnid]);
        if (!$pnote) {
            throw new NotFoundHttpException('Planner Note not Found');
        }
        $model->construction_accident_id = $pnote->id;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_name');
            if ($file) {
                $model->file_extension = $file->extension;
                $model->file_size = $file->size;
                if (!$model->name) {
                    $model->name = $file->name;
                }
                $uuid = Uuid::uuid6();
                $filename = $uuid->toString() . '.' . strtolower($model->file_extension);
                $folderpath = '@upload';
                $model->file_name = $filename;
                $model->file_path = $folderpath . '/' . $filename;
                if ($file->saveAs(Yii::getAlias($model->file_path))) {
                    if ($model->save()) {
                        $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                    } else {
                        unlink(Yii::getAlias($model->filepath));
                        $model->validate();
                        var_dump($model->errors);
                    }
                } else {
                    var_dump($file->error);
                    exit;
                }
            }
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Fetch a file of ConstructionAccidentFile model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFetchFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $filepath = Yii::getAlias($model->file_path);
        $filename = $model->name . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }

        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }

    /**
     * Deletes an existing ConstructionAccidentFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $caccident = $model->constructionAccident;

        try {
            if (unlink(Yii::getAlias($model->file_path)) && $model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->redirect(['construction-accident/view', 'id' => Yii::$app->hasher->encode($caccident->id)]);
    }


    /**
     * Finds the ConstructionAccidentFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConstructionAccidentFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConstructionAccidentFile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('construction_accident_file');
        return $this->redirect(['index']);
    }
}
