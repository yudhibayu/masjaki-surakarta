<?php

namespace app\components;

use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\DynamicModel;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * Uploader Files
 * 
 */
class Uploader
{
    /**
     * Upload Files Single
     * 
     * @param integer $id
     * @param string $nama_arsip 
     * @param string $field
     * @param string $model_path
     * @param string $upload_path
     * @return boolean
     */
    public static function upload($id = NULL, $nama_arsip = NULL, $field = NULL, $model_path = NULL, $upload_path = '.', $upload_validator_options = [])
    {
        $uuid = Uuid::uuid6();
        $model = $model_path::findOne($id);
        $file  = UploadedFile::getInstance($model, $field);
        //$file  = UploadedFile::getInstanceByName('file_name');
        $file_upload = $file;
        if (sizeof($upload_validator_options) > 0) {
            $validatorModel = new DynamicModel(compact('file_upload'));
            $validatorModel->addRule('file_upload', 'file', $upload_validator_options);
            $validatorModel->file_upload = $file_upload;
            if (!$validatorModel->validate()) {
                throw new ServerErrorHttpException('Errors : ' . json_encode($validatorModel->getErrorSummary(true)));
            }
        }

        if ($nama_arsip != NULL) { // File sudah ada sebelumnya
            if (!empty($file->baseName) && $file->size !== 0) { //file baru
                self::deleteFiles($nama_arsip, $upload_path);
                $hashed_name = $uuid->toString();
                $nama_baru = $hashed_name . '.' . $file->extension;

                $file->saveAs(Yii::getAlias('@upload') . '/' . $upload_path . '/' . $nama_baru);
                $model->$field = $nama_baru;
            } else { // tidak ada file baru
                $model->$field = $nama_arsip;
            }
        } else { //file sebelumnya belum ada
            if ($file) { //file baru
                $hashed_name = $uuid->toString();
                $nama_baru = $hashed_name . '.' . $file->extension;

                $file->saveAs(Yii::getAlias('@upload') . '/' . $upload_path . '/' . $nama_baru);
                $model->$field = $nama_baru;
            } else { //tidak ada file baru
                $model->$field = null;
            }
        }
        $model->save();
        return $model->$field;
    }

    /**
     * Upload Files Multi Non Model
     * 
     * @param integer $id
     * @param string $field
     * @param string $model_path
     * @param string $upload_path
     * @return boolean
     */
    public static function uploadMulti($id = NULL, $field = NULL, $model = NULL, $upload_path = NULL, $upload_validator_options = [])
    {
        $uuid = Uuid::uuid6();
        $files  = UploadedFile::getInstancesByName('file_name');

        $file_upload = $files;
        if (sizeof($upload_validator_options) > 0) {
            $validatorModel = new DynamicModel(compact('file_upload'));
            $validatorModel->addRule('file_upload', 'file', $upload_validator_options);
            $validatorModel->file_upload = $file_upload;
            if (!$validatorModel->validate()) {
                throw new ServerErrorHttpException('Errors : ' . json_encode($validatorModel->getErrorSummary(true)));
            }
        }

        if ($files) { //file baru
            foreach ($files as $file) {
                $hashed_name = $uuid->toString();
                $nama_baru = $hashed_name . '.' . $file->extension;

                $file->saveAs(Yii::getAlias('@upload') . '/' . $upload_path . '/' . $nama_baru);

                $model->$field = $nama_baru;
                $model->tbl_transaction_id = Yii::$app->hasher->decode($id);
                $model->save();
            }
        } else { //tidak ada file baru
            $model->$field = null;
        }

        return $model->$field;
    }

    /**
     * Delete File Upload
     * 
     * @param string $file_name
     * @param string $upload_path
     * @return boolean
     */
    public static function deleteFiles($file_name, $upload_path)
    {
        $file_lengkap = Yii::getAlias('@upload') . '/' . $upload_path . '/' . $file_name;
        $tes = file_exists($file_lengkap);
        if ($tes) {
            unlink(Yii::getAlias('@upload') . '/' . $upload_path . '/' . $file_name);
            return true;
        }
        return true;
    }
}
