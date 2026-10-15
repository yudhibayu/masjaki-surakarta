<?php

namespace app\modules\app_setting\models;

use Yii;
use yii\base\DynamicModel;

/**
 * App Setting Model
 */
class App_setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_setting';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public function init()
    {

    }

    public function getSettingEntity(){

        $sql = "
            SELECT 
                aps.*
            FROM
                app_setting aps
            WHERE 
                aps.status = 'Active'
            ORDER BY
                aps.segment_name ASC, aps.sort ASC
        ";
        $datas = Yii::$app->db->createCommand($sql)->queryAll();;

        return $datas;
    }

    public function getSettingSegment(){

        $sql = "
            SELECT DISTINCT
                aps.segment_id,
                ass.name,
                ass.sort
            FROM
                app_setting aps
            LEFT JOIN 
                app_setting_segment ass ON aps.segment_id = ass.id
            WHERE 
                aps.status = 'Active'
            ORDER BY
                ass.sort ASC
        ";
        $datas = Yii::$app->db->createCommand($sql)->queryAll();

        return $datas;
    }

    /**
     * Updates an existing App Setting model.
     * If update is successful, the browser will be redirected to the 'app_setting index' page.
     * @param array $data
     * @return void
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function simpanData($data)
    {
        unset($data['_csrf']);
        $test = $this->validateMe($data);
        if (empty($test)){
            foreach ($data as $kunci => $nilai) {
                $sql = "
                        UPDATE `app_setting` SET `value` = :theval WHERE `key` = :thekey
                ";
                $params = array(':theval' => $nilai, ':thekey' => $kunci);
                Yii::$app->db->createCommand($sql)->bindValues($params)->execute();
            }
            return $test;
        } else {
            return $test;
        }

    }

    public function validateMe($data) {
        
        $the_keys = array();
        $the_email_key = "";

        foreach ($data as $kunci => $nilai) {
            array_push($the_keys, $kunci);
            if (strpos($kunci, 'email') !== FALSE){
                $the_email_key = $kunci;
            } 
        }

        $model = new DynamicModel($data);
        $model->addRule($the_keys, 'required', ['message' => Yii::t('app', 'This input must be filled')])
              ->addRule($the_keys, 'string', ['min' => 1, 'message' => Yii::t('app', 'Must be greater than 0 characters')])
              ->addRule($the_keys, 'string', ['max'=> 70, 'message' => Yii::t('app', 'Must be less than 70 characters')]);
        
        if ($the_email_key != ""){
            $model->addRule($the_email_key, 'email', ['message' => Yii::t('app', 'Email is not valid')]);
        }

        $model->validate();


        return $model->getErrors();
    }

    /**
     * Get Setting Value
     * Based on predefined Setting Key & Value rows
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function getValue($key=null)
    {
        if ($key != null) {
        $sql = "
            SELECT DISTINCT
                aps.value
            FROM
                app_setting aps
            WHERE 
                aps.key = '{$key}'
            LIMIT
                1
        ";
        $datas = Yii::$app->db->createCommand($sql)->queryAll();
        } else {

            $datas = null;
        }
        return $datas[0]['value'];
    }

}