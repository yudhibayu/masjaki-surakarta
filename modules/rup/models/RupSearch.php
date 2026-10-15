<?php

namespace app\modules\rup\models;

use Yii;
use yii\db\Query;
use yii\base\Model;
use app\models\Satker;
use app\modules\rup\models\Rup;
use yii\data\ActiveDataProvider;

/**
 * RupSearch represents the model behind the search form of `app\modules\rup\models\Rup`.
 */
class RupSearch extends Rup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipe_rup', 'idrup', 'idmetodepengadaan', 'idjenispengadaan', 'statusdeletepaket', 'statusaktifpaket', 'idsatker', 'tipe_swakelola', 'created_by', 'updated_by'], 'integer'],
            [['klpd', 'tahunanggaran', 'namapaket', 'namasatker', 'kodesatker', 'metodepengadaan', 'jenispengadaan', 'spesifikasi', 'lokasi', 'tanggalkebutuhan', 'tanggalawalpemilihan', 'tanggalakhirpemilihan', 'tanggalawalpekerjaan', 'tanggalakhirpekerjaan', 'statuspradipa', 'statuspenyedia', 'statuspdn', 'statususahakecil', 'statusumumkan', 'keterangan', 'ppk', 'username', 'tanggalpengumuman', 'id_swakelola', 'id_rup_client', 'kd_klpd', 'nip_ppk', 'volume', 'lokasi_kab', 'lokasi_prov', 'statuspaketkonsolidasi', 'created_at', 'updated_at', 'kode_klpd_penyelenggara', 'nama_klpd_penyelenggara', 'nama_satker_penyelenggara', 'sumberdana'], 'safe'],
            [['jumlahpagu'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $marketMode = false)
    {
        $query = Rup::find();
        // add conditions that should always apply here
        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere(['like', 'tahunanggaran', $year]);
        }
        if ($marketMode) {
            $subQuery = (new Query())
                ->select('rup_code')
                ->from('pbj');
            $query->andWhere(['not in', 'idrup', $subQuery->createCommand()->queryColumn()]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if (isset(Yii::$app->session['satker']['satker_id']) && isset($year)) {
            $satker = Satker::find()->where(['fiscal_year' => $year, 'id' => Yii::$app->session['satker']['satker_id']])->one();
            if ($satker) {
                $query->andWhere(['kodesatker' => $satker->code]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tipe_rup' => $this->tipe_rup,
            'idrup' => $this->idrup,
            'jumlahpagu' => $this->jumlahpagu,
            'idmetodepengadaan' => $this->idmetodepengadaan,
            'idjenispengadaan' => $this->idjenispengadaan,
            'tanggalkebutuhan' => $this->tanggalkebutuhan,
            'tanggalawalpemilihan' => $this->tanggalawalpemilihan,
            'tanggalakhirpemilihan' => $this->tanggalakhirpemilihan,
            'tanggalawalpekerjaan' => $this->tanggalawalpekerjaan,
            'tanggalakhirpekerjaan' => $this->tanggalakhirpekerjaan,
            'tanggalpengumuman' => $this->tanggalpengumuman,
            'statusdeletepaket' => $this->statusdeletepaket,
            'statusaktifpaket' => $this->statusaktifpaket,
            'idsatker' => $this->idsatker,
            'tipe_swakelola' => $this->tipe_swakelola,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'klpd', $this->klpd])
            ->andFilterWhere(['like', 'tahunanggaran', $this->tahunanggaran])
            ->andFilterWhere(['like', 'namapaket', $this->namapaket])
            ->andFilterWhere(['like', 'namasatker', $this->namasatker])
            ->andFilterWhere(['like', 'kodesatker', $this->kodesatker])
            ->andFilterWhere(['like', 'metodepengadaan', $this->metodepengadaan])
            ->andFilterWhere(['like', 'jenispengadaan', $this->jenispengadaan])
            ->andFilterWhere(['like', 'spesifikasi', $this->spesifikasi])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'statuspradipa', $this->statuspradipa])
            ->andFilterWhere(['like', 'statuspenyedia', $this->statuspenyedia])
            ->andFilterWhere(['like', 'statuspdn', $this->statuspdn])
            ->andFilterWhere(['like', 'statususahakecil', $this->statususahakecil])
            ->andFilterWhere(['like', 'statusumumkan', $this->statusumumkan])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'ppk', $this->ppk])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'id_swakelola', $this->id_swakelola])
            ->andFilterWhere(['like', 'id_rup_client', $this->id_rup_client])
            ->andFilterWhere(['like', 'kd_klpd', $this->kd_klpd])
            ->andFilterWhere(['like', 'nip_ppk', $this->nip_ppk])
            ->andFilterWhere(['like', 'volume', $this->volume])
            ->andFilterWhere(['like', 'lokasi_kab', $this->lokasi_kab])
            ->andFilterWhere(['like', 'lokasi_prov', $this->lokasi_prov])
            ->andFilterWhere(['like', 'statuspaketkonsolidasi', $this->statuspaketkonsolidasi])
            ->andFilterWhere(['like', 'kode_klpd_penyelenggara', $this->kode_klpd_penyelenggara])
            ->andFilterWhere(['like', 'nama_klpd_penyelenggara', $this->nama_klpd_penyelenggara])
            ->andFilterWhere(['like', 'nama_satker_penyelenggara', $this->nama_satker_penyelenggara])
            ->andFilterWhere(['like', 'sumberdana', $this->sumberdana]);

        return $dataProvider;
    }
}
