<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\user\models;

use infinindotech\user\Finder;
use infinindotech\user\Mailer;
use yii\base\Model;
use infinindotech\user\models\RecoveryForm as dekRF;
/**
 * Model for collecting data on password recovery.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryForm extends dekRF
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => \Yii::t('user', 'Email'),
            'password' => \Yii::t('user', 'Password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_REQUEST => ['email'],
            self::SCENARIO_RESET => ['password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'emailTrim' => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'max' => 72, 'min' => 8],
            'passwordMatch'  => User::userPasswordValidator(['password']),
        ];
    }
}
