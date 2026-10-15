<?php
namespace app\components;

use Yii;

class Messaging
{   
    /**
     * Send Email
     * 
     * @param object $object
     * @return boolean
     */
    public function sendEmail($model)
    {
        $kirim = Yii::$app->mailer->compose('EmailNotification',[
                    'model'  => $model, 
                    'params' => Yii::$app->params,
                    ])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo(Yii::$app->user->identity->email ? Yii::$app->user->identity->email: '')
            ->setBcc(Yii::$app->params['bccEmail'])
            ->setSubject(Yii::$app->params['subjectEmail'])
            ->send();
        if ($kirim) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Controller Callback Snippet
     * 
     */

/*     use Yii;
    use app\components\Messaging;


    public function xxx($model){
        $data = false;
        $messaging = new Messaging;
        $status = $messaging->sendEmail($model);
        if ($status){
             $data = true;
        }
        return $data;
     } */
}