<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\user\controllers;

use yii\web\Controller;
use infinindotech\user\Finder;
use app\components\Theming;
use yii\filters\AccessControl;
use infinindotech\user\models\Token;
use yii\web\NotFoundHttpException;
use infinindotech\user\traits\EventTrait;
use app\overrides\user\models\RecoveryForm;
use app\overrides\user\models\User;
use infinindotech\user\traits\AjaxValidationTrait;
use infinindotech\user\controllers\RecoveryController as Testing;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * RecoveryController manages password recovery process.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryController extends Testing
{

    /**
     * Shows page where user can request password recovery.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoveryForm $model */
        $model = \Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => RecoveryForm::SCENARIO_REQUEST,
        ]);
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_REQUEST, $event);

        if ($model->load(\Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            $this->trigger(self::EVENT_AFTER_REQUEST, $event);
            Theming::setThemes(Yii::$app->params['loginTheme']);
            return $this->render('message', [
                'title'  => \Yii::t('user', 'Recovery message sent'),
                'module' => $this->module,
            ]);
        }
        Theming::setThemes(Yii::$app->params['loginTheme']);
        return $this->render('request', [
            'model' => $model,
        ]);
    }

    /**
     * Displays page where user can reset password.
     *
     * @param int    $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var Token $token */
        $token = $this->finder->findToken(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        if (empty($token) || ! $token instanceof Token) {
            throw new NotFoundHttpException();
        }
        $event = $this->getResetPasswordEvent($token);

        $this->trigger(self::EVENT_BEFORE_TOKEN_VALIDATE, $event);

        if ($token === null || $token->isExpired || $token->user === null) {
            $this->trigger(self::EVENT_AFTER_TOKEN_VALIDATE, $event);
            \Yii::$app->session->setFlash(
                'danger',
                \Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.')
            );
            Theming::setThemes(Yii::$app->params['frontEndTheme']);
            return $this->render('message', [
                'title'  => \Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        /** @var RecoveryForm $model */
        $model = \Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => RecoveryForm::SCENARIO_RESET,
        ]);
        $event->setForm($model);

        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_RESET, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            $this->trigger(self::EVENT_AFTER_RESET, $event);
            $userpwd = User::findOne(['id' => $id]);
            if ($userpwd) {
                $userpwd->pwd_expire = time() + 31556926;
                if (!$userpwd->save()) {
                    throw new ServerErrorHttpException("Gagal menyimpan tanggal password baru".json_encode($model));
                }
            }
            else{
                throw new ServerErrorHttpException("Gagal menyimpan tanggal password baru".json_encode($model));
            }
            Theming::setThemes(Yii::$app->params['loginTheme']);
            return $this->render('message', [
                'title'  => \Yii::t('user', 'Password has been changed'),
                'module' => $this->module,
            ]);
        }
        Theming::setThemes(Yii::$app->params['loginTheme']);
        return $this->render('reset', [
            'model' => $model,
        ]);
    }
}
