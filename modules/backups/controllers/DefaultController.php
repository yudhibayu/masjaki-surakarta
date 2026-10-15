<?php

namespace app\modules\backups\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * Default controller for the `backups` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Administrator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $backup = Yii::$app->backup;
        $backups = FileHelper::findFiles(Yii::getAlias($backup->backupDir), [
            'except' => ['.gitignore']
        ]);
        $dataBackup = [];
        $id = 1;
        foreach ($backups as $key => $value) {
            $explodedvalue = explode('/', $value);
            $lastElement = end($explodedvalue);
            $explodeddatetime = explode('_', $lastElement);
            $datetime = date('Y-m-d H:i:s', strtotime($explodeddatetime[0]));
            array_push($dataBackup, ['id' => $id, 'name' => $lastElement, 'datetime' => $datetime]);
            $id++;
        }
        return $this->render('index', [
            'backups'   =>  $dataBackup,
        ]);
    }

    public function actionBackup()
    {
        $backup = Yii::$app->backup;
        // $db = 'db';
        // // $index = (string)1;
        // $backup->fileName = 'jdih';
        // $backup->fileName .= str_pad($index, 3, '0', STR_PAD_LEFT);
        // $backup->directories = [];
        // $backup->databases = [$db];
        $file = $backup->create();
        // $this->stdout('Backup file created: ' . $file . PHP_EOL, \yii\helpers\Console::FG_GREEN);
        Yii::$app->response->sendFile($file);
    }

    public function actionDelete($id)
    {
        $flash = ['status' => 'error', 'message' => Yii::t('app', 'File not deleted')];
        $backup = Yii::$app->backup;
        $backups = FileHelper::findFiles(Yii::getAlias($backup->backupDir), [
            'only' => [$id]
        ]);
        if (strpos('..', $id) !== false) {
        } else if (sizeof($backups) == 1) {
            if (FileHelper::unlink($backups[0])) {
                $flash['status']  = 'success';
                $flash['message'] = Yii::t('app', 'File successfully deleted');
            }
        }
        Yii::$app->session->setFlash($flash['status'], $flash['message']);
        return $this->redirect(['index']);
    }

    public function actionDownload($id)
    {
        $flash = ['status' => 'error', 'message' => Yii::t('app', 'File not detected')];
        $backup = Yii::$app->backup;
        $backups = FileHelper::findFiles(Yii::getAlias($backup->backupDir), [
            'only' => [$id]
        ]);
        if (strpos('..', $id) !== false) {
        } else if (sizeof($backups) == 1) {
            $flash['status']  = 'success';
            $flash['message'] = Yii::t('app', 'File successfully detected');
            return Yii::$app->response->sendFile($backups[0]);
        }
        Yii::$app->session->setFlash($flash['status'], $flash['message']);
        return $this->redirect(['index']);
    }

    public function actionRemoveBackup()
    {
        $backup = Yii::$app->backup;
        $backup->deleteDeprecated();
        return $this->redirect(['index']);
    }
}
