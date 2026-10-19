<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\referencesMasjaki\models\MasterProdukHukum;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MasterProdukHukumController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => ['class' => AccessControl::class, 'rules' => [['allow' => true, 'roles' => ['Administrator']]]],
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['toggle' => ['POST']]],
        ];
    }

    public function actionIndex($nama = null)
    {
        $query = MasterProdukHukum::find()->orderBy('nama');
        if ($nama !== null && trim($nama) !== '') {
            $query->andWhere(['like', 'nama', trim($nama)]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20], 'sort' => false]),
            'nama' => $nama,
        ]);
    }

    public function actionCreate()
    {
        return $this->edit(new MasterProdukHukum());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionToggle($id)
    {
        $record = $this->findRecord($id);
        $record->is_active = !$record->is_active;
        if (!$record->save()) {
            throw new \RuntimeException('Gagal mengubah status master.');
        }
        return $this->redirect(['index']);
    }

    private function edit(MasterProdukHukum $record)
    {
        if ($record->load(Yii::$app->request->post()) && $record->save()) {
            Yii::$app->session->setFlash('success', 'Master produk hukum berhasil disimpan.');
            return $this->redirect(['index']);
        }
        return $this->render('form', ['record' => $record]);
    }

    private function findRecord($id)
    {
        $record = MasterProdukHukum::findOne($id);
        if ($record === null) {
            throw new NotFoundHttpException('Master tidak ditemukan.');
        }
        return $record;
    }
}
