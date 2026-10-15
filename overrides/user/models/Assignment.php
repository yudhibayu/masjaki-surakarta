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

use dektrium\rbac\components\DbManager;
use dektrium\rbac\validators\RbacValidator;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use dektrium\rbac\models\Assignment as Asgn;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Assignment extends Asgn
{

    /**
     * Returns all available auth items to be attached to user.
     * @return array
     */
    public function getAvailableItems()
    {
        if (!Yii::$app->user->can('Super Administrator')){
            return ArrayHelper::map($this->manager->getItems(1,['Super Administrator']), 'name', function ($item) {
                return empty($item->description)
                    ? $item->name
                    : $item->name . ' (' . $item->description . ')';
            });
        } else {
            return ArrayHelper::map($this->manager->getItems(), 'name', function ($item) {
                return empty($item->description)
                    ? $item->name
                    : $item->name . ' (' . $item->description . ')';
            });
        }
    }
}