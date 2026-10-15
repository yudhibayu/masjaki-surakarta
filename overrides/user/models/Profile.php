<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace app\overrides\user\models;

use infinindotech\user\models\Profile as InfinindotechProfile;
use app\modules\manage_unit\models\RefUnit;
use Yii;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Profile extends InfinindotechProfile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'bioString'            => ['bio', 'string'],
            'timeZoneValidation'   => ['timezone', 'validateTimeZone'],
            'publicEmailPattern'   => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl'           => ['website', 'url'],
            'nameLength'           => ['name', 'string', 'max' => 255],
            'publicEmailLength'    => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength'  => ['gravatar_email', 'string', 'max' => 255],
            'locationLength'       => ['location', 'string', 'max' => 255],
            'websiteLength'        => ['website', 'string', 'max' => 255],
            'fileLength'           => ['file_name', 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'               => \Yii::t('user', 'Full Name'),
            'public_email'       => \Yii::t('user', 'Email (public)'),
            'gravatar_email'     => \Yii::t('user', 'Gravatar email'),
            'location'           => \Yii::t('user', 'Location'),
            'website'            => \Yii::t('user', 'Website'),
            'bio'                => \Yii::t('user', 'Bio'),
            'timezone'           => \Yii::t('user', 'Time zone'),
            'file_name'          => \Yii::t('app', 'Profile Picture'),
        ];
    }

}