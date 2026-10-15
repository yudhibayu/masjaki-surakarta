<?php

namespace app\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Vtender;
use yii;

class DigsigController extends \yii\web\Controller {

/* public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'getdata'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'getdata', 'getcolumns'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }*/



	public function actionIndex() {
		$this->layout = 'digsig';
        $model = new Vtender;
        $isidata = $model->get_digsig();	
		return $this->render('index',['isi'=>$isidata]);
	
	}


	public function actionGetdata() {

		//$isidata = Vtender::find()->asArray()->all();
 		//echo json_encode($isidata);
 		$model = new Vtender;
        $isidata = $model->get_digsig();

        $response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->data = array($isidata);
	}	

    public function actionGetstatustahun() {

        $model = new Vtender;
        $isidata = $model->get_status_tahun_upcoming();
        //echo var_dump($isidata);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $isidata;
    }



}
