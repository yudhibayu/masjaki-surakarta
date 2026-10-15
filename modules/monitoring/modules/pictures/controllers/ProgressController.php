<?php

namespace app\modules\monitoring\modules\pictures\controllers;

use app\modules\monitoring\controllers\ProgressFileController;
use Yii;

/**
 * ProgressController implements the CRUD actions for MonitoringProgressPictures model.
 */
class ProgressController extends ProgressFileController
{
    public function getViewPath()
    {
        return Yii::getAlias('@app/modules/monitoring/views/progress-file');
    }
}
