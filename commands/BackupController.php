<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BackupController extends Controller
{
    /**
     * @return int Exit code
     */
    public function actionIndex()
    {
        $backup = \Yii::$app->backup;
        $file = $backup->create();
        $deprecated = $backup->deleteDeprecated();
        $this->stdout('Backup file created: ' . $file . PHP_EOL);
        $this->stdout('Expired : '.$deprecated['expiredFiles']. PHP_EOL, \yii\helpers\Console::FG_PURPLE);
        $this->stdout('Deleted : '.$deprecated['deletedFiles']. PHP_EOL, \yii\helpers\Console::FG_PURPLE);
        return ExitCode::OK;
    }
}
