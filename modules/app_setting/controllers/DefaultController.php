<?php

namespace app\modules\app_setting\controllers;

use app\modules\app_setting\models\App_setting as App_setting;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Yii;


/**
 * Default controller for the `setting` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'simpan'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'simpan'],
                        'roles' => ['Super Administrator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new App_setting;
        $segment = $model->getSettingSegment();
		$data = $model->getSettingEntity();
        $req_data = null;
        $cek = null;

        if (Yii::$app->request->post()){
            $req_data = Yii::$app->request->post();
            //echo var_dump($req_data);
            
            if ($req_data){
                $model = new App_setting;
                $periksa_simpan = $model->simpanData($req_data);
                if ($periksa_simpan){
                    $cek = $periksa_simpan;
                }
            }
        }

        return $this->render('index', array("model"=>$model, "segment"=>$segment, "data"=>$data, "req_data"=>$req_data, "errors"=>$cek));
    }

    /**
     * Updates an existing App Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSimpan()
    {


        
    }



}
