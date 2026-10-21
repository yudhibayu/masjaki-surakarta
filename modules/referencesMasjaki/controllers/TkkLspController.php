<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\referencesMasjaki\models\MasterLsp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class TkkLspController extends TkkBaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['toggle' => ['POST']]],
        ]);
    }

    public function actionIndex($q = null)
    {
        $query = MasterLsp::find()->orderBy(['nama_lsp' => SORT_ASC]);
        if (trim((string) $q) !== '') {
            $query->andWhere(['like', 'nama_lsp', trim($q)]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20], 'sort' => false]),
            'q' => $q,
        ]);
    }

    public function actionCreate()
    {
        return $this->edit(new MasterLsp());
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
            throw new \RuntimeException('Status LSP gagal diubah.');
        }
        return $this->redirect(['index']);
    }

    private function edit(MasterLsp $record)
    {
        if ($record->load(Yii::$app->request->post()) && $record->save()) {
            Yii::$app->session->setFlash('success', 'LSP berhasil disimpan.');
            return $this->redirect(['index']);
        }
        return $this->render('form', ['record' => $record]);
    }

    private function findRecord($id)
    {
        $record = MasterLsp::findOne($id);
        if ($record === null) {
            throw new NotFoundHttpException('LSP tidak ditemukan.');
        }
        return $record;
    }
}
