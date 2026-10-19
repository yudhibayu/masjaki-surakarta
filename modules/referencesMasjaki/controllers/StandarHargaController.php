<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\StandarHargaService;
use app\components\StandarHargaStorage;
use app\modules\referencesMasjaki\models\MasterProdukHukum;
use app\modules\referencesMasjaki\models\ProdukHukumForm;
use app\modules\referencesMasjaki\models\ProdukHukumTahunan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class StandarHargaController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => ['class' => AccessControl::class, 'rules' => [['allow' => true, 'roles' => ['Administrator']]]],
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['POST']]],
        ];
    }

    public function actionIndex($tahun = null, $master = null, $is_public = null)
    {
        $query = ProdukHukumTahunan::find()->where(['deleted_at' => null])->with('master')->orderBy(['tahun' => SORT_DESC, 'id' => SORT_DESC]);
        if ($tahun !== null && preg_match('/^\d{4}$/', (string) $tahun)) {
            $query->andWhere(['tahun' => (int) $tahun]);
        }
        if ($master !== null && ctype_digit((string) $master)) {
            $query->andWhere(['master_produk_hukum_id' => (int) $master]);
        }
        if ($is_public !== null && in_array((string) $is_public, ['0', '1'], true)) {
            $query->andWhere(['is_public' => (int) $is_public]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20], 'sort' => false]),
            'tahun' => $tahun, 'master' => $master, 'is_public' => $is_public,
            'masters' => ArrayHelper::map(MasterProdukHukum::find()->orderBy('nama')->all(), 'id', 'nama'),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['record' => $this->findRecord($id)]);
    }

    public function actionCreate()
    {
        return $this->edit(new ProdukHukumTahunan());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionDelete($id)
    {
        if (!(new StandarHargaService())->delete($this->findRecord($id))) {
            throw new \RuntimeException('Gagal menghapus dokumen.');
        }
        Yii::$app->session->setFlash('success', 'Dokumen berhasil diarsipkan.');
        return $this->redirect(['index']);
    }

    public function actionLihat($id)
    {
        return StandarHargaStorage::send($id, true, false);
    }

    public function actionUnduh($id)
    {
        return StandarHargaStorage::send($id, false, false);
    }

    private function edit(ProdukHukumTahunan $record)
    {
        $isNew = $record->isNewRecord;
        $form = new ProdukHukumForm();
        $form->master_produk_hukum_id = $record->master_produk_hukum_id;
        $form->tahun = $record->tahun;
        $form->is_public = $isNew ? 0 : $record->is_public;
        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            $form->file = UploadedFile::getInstance($form, 'file');
            if ((new StandarHargaService())->save($record, $form, !$isNew)) {
                Yii::$app->session->setFlash('success', 'Dokumen produk hukum berhasil disimpan.');
                $saved = $isNew ? ProdukHukumTahunan::findOne([
                    'master_produk_hukum_id' => $form->master_produk_hukum_id, 'tahun' => $form->tahun,
                ]) : $record;
                return $this->redirect(['view', 'id' => $saved->id]);
            }
        }
        $active = MasterProdukHukum::find()->where(['is_active' => 1])->orderBy('nama')->all();
        $options = ArrayHelper::map($active, 'id', 'nama');
        if (!$isNew && $record->master !== null && !isset($options[$record->master_produk_hukum_id])) {
            $options[$record->master_produk_hukum_id] = $record->master->nama . ' (nonaktif)';
        }
        return $this->render('form', ['formModel' => $form, 'record' => $record, 'masters' => $options]);
    }

    private function findRecord($id)
    {
        $record = ProdukHukumTahunan::find()->where(['id' => $id, 'deleted_at' => null])->with('master')->one();
        if ($record === null) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        return $record;
    }
}
