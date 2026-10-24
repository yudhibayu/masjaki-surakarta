<?php

namespace app\modules\monitoring\controllers;

use app\components\TertibDokumenService;
use app\components\TertibDokumenStorage;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\TertibChecklist;
use app\modules\monitoring\models\TertibDokumen;
use app\modules\monitoring\models\TertibPengawasan;
use app\modules\monitoring\models\TertibTemuan;
use app\modules\monitoring\models\TertibTindakLanjut;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TertibController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Administrator', 'Super Administrator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'checklist-delete' => ['POST'],
                    'temuan-delete' => ['POST'],
                    'dokumen-delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->get();
        $yearValue = $params['tahun'] ?? '';
        if (!is_scalar($yearValue)) {
            throw new \yii\web\BadRequestHttpException('Tahun anggaran tidak valid.');
        }
        $year = (string) $yearValue;
        if ($year !== '' && !preg_match('/\A\d{4}\z/', $year)) {
            throw new \yii\web\BadRequestHttpException('Tahun anggaran tidak valid.');
        }

        $query = TertibPengawasan::find()->joinWith(['monitoringTask']);
        if ($year !== '') {
            $query->andWhere(['monitoring_task.fiscal_year' => (int) $year]);
        }
        if (isset($params['publikasi']) && is_scalar($params['publikasi']) && in_array((string) $params['publikasi'], ['0', '1'], true)) {
            $query->andWhere(['pengawasan_tertib.is_public' => (int) $params['publikasi']]);
        }
        if (isset($params['status']) && is_string($params['status']) && isset(TertibPengawasan::statusOptions()[$params['status']])) {
            $query->andWhere(['pengawasan_tertib.status_pelaksanaan' => $params['status']]);
        }
        $keywordValue = $params['q'] ?? '';
        $keyword = is_scalar($keywordValue) ? mb_substr(trim((string) $keywordValue), 0, 120) : '';
        if ($keyword !== '') {
            $query->andWhere([
                'or',
                ['like', 'monitoring_task.name', $keyword],
                ['like', 'monitoring_task.contractor_name', $keyword],
            ]);
        }
        $query->orderBy(['monitoring_task.fiscal_year' => SORT_DESC, 'pengawasan_tertib.tanggal_pengawasan' => SORT_DESC]);

        $years = MonitoringTask::find()->select('fiscal_year')->distinct()
            ->where(['>', 'fiscal_year', 0])->orderBy(['fiscal_year' => SORT_DESC])->column();
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 20],
            ]),
            'params' => [
                'tahun' => $year,
                'publikasi' => isset($params['publikasi']) && is_scalar($params['publikasi']) && in_array((string) $params['publikasi'], ['0', '1'], true)
                    ? (string) $params['publikasi'] : '',
                'status' => isset($params['status']) && is_string($params['status']) && isset(TertibPengawasan::statusOptions()[$params['status']])
                    ? $params['status'] : '',
                'q' => $keyword,
            ],
            'years' => array_combine($years, $years) ?: [],
        ]);
    }

    public function actionView($id)
    {
        $model = TertibPengawasan::find()
            ->with(['monitoringTask', 'checklists', 'findings.tindakLanjuts', 'documents'])
            ->where(['id' => $this->normalizeId($id)])
            ->one();
        if ($model === null) {
            throw new NotFoundHttpException('Data pengawasan tidak ditemukan.');
        }
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new TertibPengawasan();
        $model->is_public = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Kegiatan pengawasan berhasil disimpan.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
            'taskOptions' => $this->taskOptions($model->monitoring_task_id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel(TertibPengawasan::class, $id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Kegiatan pengawasan berhasil diperbarui.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'taskOptions' => $this->taskOptions($model->monitoring_task_id),
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel(TertibPengawasan::class, $id);
        if ($model->getChecklists()->exists() || $model->getFindings()->exists() || $model->getDocuments()->exists()) {
            Yii::$app->session->setFlash('warning', 'Kegiatan tidak dapat dihapus karena sudah memiliki checklist, temuan, atau dokumen.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->delete();
        Yii::$app->session->setFlash('success', 'Kegiatan pengawasan berhasil dihapus.');
        return $this->redirect(['index']);
    }

    public function actionChecklistCreate($pengawasanId)
    {
        $parent = $this->findModel(TertibPengawasan::class, $pengawasanId);
        $model = new TertibChecklist();
        $model->pengawasan_id = $parent->id;
        if ($model->load(Yii::$app->request->post())) {
            $model->pengawasan_id = $parent->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Item checklist berhasil disimpan.');
                return $this->redirect(['view', 'id' => $parent->id]);
            }
        }
        return $this->render('checklist-form', ['model' => $model, 'parent' => $parent]);
    }

    public function actionChecklistUpdate($id)
    {
        $model = $this->findModel(TertibChecklist::class, $id);
        $parentId = (int) $model->pengawasan_id;
        $parent = $this->findModel(TertibPengawasan::class, $parentId);
        if ($model->load(Yii::$app->request->post())) {
            $model->pengawasan_id = $parentId;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Item checklist berhasil diperbarui.');
                return $this->redirect(['view', 'id' => $parentId]);
            }
        }
        return $this->render('checklist-form', ['model' => $model, 'parent' => $parent]);
    }

    public function actionChecklistDelete($id)
    {
        $model = $this->findModel(TertibChecklist::class, $id);
        $parentId = (int) $model->pengawasan_id;
        $model->delete();
        Yii::$app->session->setFlash('success', 'Item checklist berhasil dihapus.');
        return $this->redirect(['view', 'id' => $parentId]);
    }

    public function actionTemuanCreate($pengawasanId)
    {
        $parent = $this->findModel(TertibPengawasan::class, $pengawasanId);
        $model = new TertibTemuan();
        $model->pengawasan_id = $parent->id;
        $model->is_public = 0;
        $model->status_tindak_lanjut = TertibTemuan::BELUM;
        if ($model->load(Yii::$app->request->post())) {
            $model->pengawasan_id = $parent->id;
            $model->status_tindak_lanjut = TertibTemuan::BELUM;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Temuan berhasil disimpan.');
                return $this->redirect(['view', 'id' => $parent->id]);
            }
        }
        return $this->render('temuan-form', ['model' => $model, 'parent' => $parent]);
    }

    public function actionTemuanUpdate($id)
    {
        $model = $this->findModel(TertibTemuan::class, $id);
        $parentId = (int) $model->pengawasan_id;
        $currentStatus = $model->status_tindak_lanjut;
        $parent = $this->findModel(TertibPengawasan::class, $parentId);
        if ($model->load(Yii::$app->request->post())) {
            $model->pengawasan_id = $parentId;
            $model->status_tindak_lanjut = $currentStatus;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Temuan berhasil diperbarui.');
                return $this->redirect(['view', 'id' => $parentId]);
            }
        }
        return $this->render('temuan-form', ['model' => $model, 'parent' => $parent]);
    }

    public function actionTemuanDelete($id)
    {
        $model = $this->findModel(TertibTemuan::class, $id);
        $parentId = (int) $model->pengawasan_id;
        if ($model->getTindakLanjuts()->exists()) {
            Yii::$app->session->setFlash('warning', 'Temuan tidak dapat dihapus karena sudah memiliki riwayat tindak lanjut.');
            return $this->redirect(['view', 'id' => $parentId]);
        }
        $model->delete();
        Yii::$app->session->setFlash('success', 'Temuan berhasil dihapus.');
        return $this->redirect(['view', 'id' => $parentId]);
    }

    public function actionTindakLanjutCreate($temuanId)
    {
        $finding = $this->findModel(TertibTemuan::class, $temuanId);
        $parentId = (int) $finding->pengawasan_id;
        $parent = $this->findModel(TertibPengawasan::class, $parentId);
        $model = new TertibTindakLanjut();
        $model->temuan_id = $finding->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->temuan_id = $finding->id;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $finding->status_tindak_lanjut = $model->status;
                    if (!$finding->save(false)) {
                        throw new \RuntimeException('Status temuan tidak dapat diperbarui.');
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Riwayat tindak lanjut berhasil dicatat.');
                    return $this->redirect(['view', 'id' => $parentId]);
                }
                $transaction->rollBack();
            } catch (\Throwable $error) {
                if ($transaction->isActive) {
                    $transaction->rollBack();
                }
                throw $error;
            }
        }
        return $this->render('tindak-lanjut-form', [
            'model' => $model,
            'finding' => $finding,
            'parent' => $parent,
        ]);
    }

    public function actionDokumenUpload($id)
    {
        $parent = $this->findModel(TertibPengawasan::class, $id);
        $model = new TertibDokumen();
        $model->pengawasan_id = $parent->id;
        if ($model->load(Yii::$app->request->post())) {
            $model->pengawasan_id = $parent->id;
            $model->upload = UploadedFile::getInstance($model, 'upload');
            if ((new TertibDokumenService())->upload($model)) {
                Yii::$app->session->setFlash('success', 'Dokumen berhasil diunggah. Berkas ini hanya dapat diakses oleh petugas berwenang.');
                return $this->redirect(['view', 'id' => $parent->id]);
            }
        }
        return $this->render('document-upload', ['model' => $model, 'parent' => $parent]);
    }

    public function actionDokumenDownload($id)
    {
        $document = $this->findModel(TertibDokumen::class, $id);
        $response = Yii::$app->response->sendFile(
            TertibDokumenStorage::path($document->file_path),
            TertibDokumenStorage::safeName($document->nama_file),
            ['inline' => false, 'mimeType' => $document->mime_type]
        );
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Cache-Control', 'no-store');
        return $response;
    }

    public function actionDokumenDelete($id)
    {
        $document = $this->findModel(TertibDokumen::class, $id);
        $parentId = (int) $document->pengawasan_id;
        (new TertibDokumenService())->delete($document);
        Yii::$app->session->setFlash('success', 'Dokumen berhasil dihapus.');
        return $this->redirect(['view', 'id' => $parentId]);
    }

    private function taskOptions($selectedId = null)
    {
        $query = MonitoringTask::find()->select(['id', 'fiscal_year', 'name', 'unit_name'])
            ->where(['is_active' => 1]);
        if ($selectedId) {
            $query->orWhere(['id' => (int) $selectedId]);
        }
        $options = [];
        foreach ($query->orderBy(['fiscal_year' => SORT_DESC, 'name' => SORT_ASC])->asArray()->all() as $task) {
            $label = trim((string) $task['fiscal_year'] . ' — ' . (string) $task['name']);
            if ($task['unit_name'] !== '') {
                $label .= ' (' . $task['unit_name'] . ')';
            }
            $options[$task['id']] = $label;
        }
        return $options;
    }

    private function findModel($class, $id)
    {
        $modelId = $this->normalizeId($id);
        $model = $class::findOne($modelId);
        if ($model === null) {
            throw new NotFoundHttpException('Data tidak ditemukan.');
        }
        return $model;
    }

    private function normalizeId($id)
    {
        if ((!is_string($id) && !is_int($id)) || !ctype_digit((string) $id) || (int) $id < 1) {
            throw new NotFoundHttpException('Data tidak ditemukan.');
        }
        return (int) $id;
    }
}
