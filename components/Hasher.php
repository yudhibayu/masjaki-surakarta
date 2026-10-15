<?php
namespace app\components;

use Yii;
use yii\web\NotFoundHttpException;

class Hasher
{
	/**
     * Encode Data
     *
     * @return string
     */
    public static function encode($id)
    {
        return Yii::$app->hashids->encode($id);
    }

	/**
     * Decode Data
     *
     * @return string
     */
    public static function decode($id)
    {
        $id = Yii::$app->hashids->decode($id);

        if ($id){
            return $id[0]; 
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}