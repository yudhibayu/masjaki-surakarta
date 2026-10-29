<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\RekapPembinaan;
use app\modules\referencesMasjaki\models\JenisKegiatanPembinaan;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class PembinaanController extends PembinaanBaseController
{
    public function actionIndex($tahun = null, $jenis = null)
    {
        if ($tahun === '') {
            $tahun = null;
        }
        if ($jenis === '') {
            $jenis = null;
        }
        if ($tahun !== null && (!preg_match('/^\d{4}$/', (string) $tahun) || (int) $tahun < 1900 || (int) $tahun > 2100)) {
            throw new BadRequestHttpException('Filter tahun tidak valid.');
        }
        if ($jenis !== null && (!ctype_digit((string) $jenis) || (int) $jenis < 1)) {
            throw new BadRequestHttpException('Filter jenis kegiatan tidak valid.');
        }

        return $this->render('index', [
            'selectedYear' => $tahun === null ? null : (int) $tahun,
            'selectedType' => $jenis === null ? null : (int) $jenis,
            'typeOptions' => ArrayHelper::map(
                JenisKegiatanPembinaan::find()->where(['deleted_at' => null])->orderBy('nama')->all(),
                'id',
                'nama'
            ),
            'report' => RekapPembinaan::summary($tahun, false, $jenis),
        ]);
    }
}
