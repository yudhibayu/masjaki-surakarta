<?php

namespace app\modules\rup\models;

use app\models\Satker;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "rup".
 *
 * @property boolean $is_sync Harus TRUE jika data disinkron menggunakan api
 * @property int $tipe_rup Tipe RUP
 * @property string|null $namecode $idrup and $namapaket combined
 * @property string|null $klpd
 * @property string|null $tahunanggaran
 * @property int $idrup
 * @property int $id Shortcut for $idrup
 * @property string|null $namapaket
 * @property string|null $name Shortcut for $namapaket
 * @property float|null $jumlahpagu
 * @property string|null $namasatker
 * @property string|null $kodesatker
 * @property string|null $metodepengadaan
 * @property int|null $idmetodepengadaan
 * @property string|null $jenispengadaan
 * @property int|null $idjenispengadaan
 * @property string|null $spesifikasi
 * @property string|null $lokasi
 * @property string|null $tanggalkebutuhan
 * @property string|null $tanggalawalpemilihan
 * @property string|null $tanggalakhirpemilihan
 * @property string|null $tanggalawalpekerjaan
 * @property string|null $tanggalakhirpekerjaan
 * @property string|null $statuspradipa
 * @property string|null $statuspenyedia
 * @property string|null $statuspdn
 * @property string|null $statususahakecil
 * @property string|null $statusumumkan
 * @property string|null $keterangan
 * @property string|null $ppk
 * @property string|null $username
 * @property string|null $tanggalpengumuman
 * @property string|null $id_swakelola
 * @property int|null $statusdeletepaket
 * @property int|null $statusaktifpaket
 * @property string|null $id_rup_client
 * @property string|null $kd_klpd
 * @property string|null $nip_ppk
 * @property string|null $volume
 * @property string|null $lokasi_kab
 * @property string|null $lokasi_prov
 * @property string|null $statuspaketkonsolidasi
 * @property int|null $idsatker
 * @property int|null $tipe_swakelola
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $kode_klpd_penyelenggara
 * @property string|null $nama_klpd_penyelenggara
 * @property string|null $nama_satker_penyelenggara
 * @property string|null $sumberdana
 * 
 * @property Satker $satker
 */
class Rup extends \yii\db\ActiveRecord
{
    const TIPE_RUP_PENYEDIA = 0;
    const TIPE_RUP_SWAKELOLA = 1;

    public $is_sync = false;

    public $sess;
    public $id;
    public $name;
    public $namecode;
    public $namecodeannounced;

    // Data Untuk Attribut Pilihan
    // const SELECT_METODE_PENGADAAN = [
    //     7 => 'Penunjukan Langsung',
    //     8 => 'Pengadaan Langsung',
    //     9 => 'e-Purchasing',
    //     10 => 'Sayembara',
    //     11 => 'Kontes',
    //     13 => 'Tender',
    //     14 => 'Tender Cepat',
    //     15 => 'Seleksi',
    //     16 => 'Dikecualikan',
    //     17 => 'Darurat',
    //     20 => 'Pembayaran untuk Kontrak Tahun Jamak ',
    // ];

    // const SELECT_JENIS_PENGADAAN = [
    //     1 => 'Barang',
    //     2 => 'Pekerjaan Konstruksi',
    //     3 => 'Jasa Konsultansi',
    //     4 => 'Jasa Lainnya',
    // ];

    const SELECT_PRADIPA = [
        'bukanPaketPradipa' => 'Bukan Pradipa',
        'paketPradipa' => 'Pradipa',
    ];

    const SELECT_PENYEDIA = [
        'penyediaDalamSwakelola' => 'Penyedia Dalam Swakelola',
        'penyediaMurni' => 'Penyedia Murni',
    ];

    const SELECT_PDN = [
        'PDN' => 'PDN',
        'Non-PDN' => 'Tidak PDN',
    ];

    const SELECT_USAHA_KECIL = [
        'bukanUsahaKecil' => 'Bukan Usaha Kecil',
        'UsahaKecil' => 'Usaha Kecil',
    ];

    const SELECT_STATUS_UMUMKAN = [
        'Draf Lengkap' => 'Draf Lengkap',
        'Final Draft' => 'Final Draft',
        'Inisiasi Revisi Satu ke Banyak' => 'Inisiasi Revisi Satu ke Banyak',
        'Inisiasi Revisi Satu ke Satu' => 'Inisiasi Revisi Satu ke Satu',
        'Konsolidasi PPK' => 'Konsolidasi PPK',
        'Konsolidasi Satu Satker' => 'Konsolidasi Satu Satker',
        'Terdelete' => 'Terdelete',
        'Terinisiasi' => 'Terinisiasi',
        'Terrevisi Batal' => 'Terrevisi Batal',
        'Terrevisi Satu ke Banyak' => 'Terrevisi Satu ke Banyak',
        'Terrevisi Satu ke Satu' => 'Terrevisi Satu ke Satu',
        'Terumumkan' => 'Terumumkan',
    ];

    const SELECT_PAKET_KONSOLIDASI = [
        'PaketKonsolidasi' => 'Paket Konsolidasi',
        'PaketNonKonsolidasi' => 'Paket Non Konsolidasi',
    ];

    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        if (isset(Yii::$app->session)) {
            $this->sess = Yii::$app->session;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rup';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * Model Behaviors
     * Blameable = table field harus ada: created_by dan updated_by
     * Timestamp = table field harus ada: created_at dan updated_at
     * @return void
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipe_rup', 'idrup', 'idmetodepengadaan', 'idjenispengadaan', 'statusdeletepaket', 'statusaktifpaket', 'idsatker', 'created_by', 'updated_by'], 'integer'],
            [['idrup'], 'required'],
            [['namapaket', 'namasatker', 'spesifikasi', 'lokasi', 'keterangan', 'volume', 'lokasi_kab', 'lokasi_prov', 'nama_satker_penyelenggara'], 'string'],
            [['jumlahpagu'], 'number'],
            [['tanggalkebutuhan', 'tanggalawalpemilihan', 'tanggalakhirpemilihan', 'tanggalawalpekerjaan', 'tanggalakhirpekerjaan', 'tanggalpengumuman', 'created_at', 'updated_at'], 'safe'],
            [['klpd', 'tahunanggaran', 'kodesatker', 'metodepengadaan', 'jenispengadaan', 'statuspradipa', 'statuspenyedia', 'statuspdn', 'statususahakecil', 'statusumumkan', 'ppk', 'username', 'id_swakelola', 'id_rup_client', 'kd_klpd', 'nip_ppk', 'statuspaketkonsolidasi', 'kode_klpd_penyelenggara', 'nama_klpd_penyelenggara'], 'string', 'max' => 255],
            [['idrup'], 'unique'],
            [['tipe_rup', 'namapaket', 'jumlahpagu', 'kodesatker'], 'required'],
            [
                ['tipe_swakelola',],
                'required',
                'when' => function ($model) {
                    return ($model->tipe_rup == Rup::TIPE_RUP_SWAKELOLA);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#tipe_rup').val() == '" . Rup::TIPE_RUP_SWAKELOLA . "';
                }",
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tipe_rup' => Yii::t('rup', 'Tipe RUP'),
            'klpd' => Yii::t('rup', 'Klpd'),
            'tahunanggaran' => Yii::t('rup', 'Tahunanggaran'),
            'idrup' => Yii::t('rup', 'Idrup'),
            'namecode' => Yii::t('rup', 'RUP'),
            'namapaket' => Yii::t('rup', 'Namapaket'),
            'jumlahpagu' => Yii::t('rup', 'Jumlahpagu'),
            'namasatker' => Yii::t('rup', 'Namasatker'),
            'kodesatker' => Yii::t('rup', 'Kodesatker'),
            'metodepengadaan' => Yii::t('rup', 'Metodepengadaan'),
            'idmetodepengadaan' => Yii::t('rup', 'Idmetodepengadaan'),
            'jenispengadaan' => Yii::t('rup', 'Jenispengadaan'),
            'idjenispengadaan' => Yii::t('rup', 'Idjenispengadaan'),
            'spesifikasi' => Yii::t('rup', 'Spesifikasi'),
            'lokasi' => Yii::t('rup', 'Lokasi'),
            'tanggalkebutuhan' => Yii::t('rup', 'Tanggalkebutuhan'),
            'tanggalawalpemilihan' => Yii::t('rup', 'Tanggalawalpemilihan'),
            'tanggalakhirpemilihan' => Yii::t('rup', 'Tanggalakhirpemilihan'),
            'tanggalawalpekerjaan' => Yii::t('rup', 'Tanggalawalpekerjaan'),
            'tanggalakhirpekerjaan' => Yii::t('rup', 'Tanggalakhirpekerjaan'),
            'statuspradipa' => Yii::t('rup', 'Statuspradipa'),
            'statuspenyedia' => Yii::t('rup', 'Statuspenyedia'),
            'statuspdn' => Yii::t('rup', 'Statuspdn'),
            'statususahakecil' => Yii::t('rup', 'Statususahakecil'),
            'statusumumkan' => Yii::t('rup', 'Statusumumkan'),
            'keterangan' => Yii::t('rup', 'Keterangan'),
            'ppk' => Yii::t('rup', 'Ppk'),
            'username' => Yii::t('rup', 'Username'),
            'tanggalpengumuman' => Yii::t('rup', 'Tanggalpengumuman'),
            'id_swakelola' => Yii::t('rup', 'Id Swakelola'),
            'statusdeletepaket' => Yii::t('rup', 'Statusdeletepaket'),
            'statusaktifpaket' => Yii::t('rup', 'Statusaktifpaket'),
            'id_rup_client' => Yii::t('rup', 'Id Rup Client'),
            'kd_klpd' => Yii::t('rup', 'Kd Klpd'),
            'nip_ppk' => Yii::t('rup', 'Nip Ppk'),
            'volume' => Yii::t('rup', 'Volume'),
            'lokasi_kab' => Yii::t('rup', 'Lokasi Kab'),
            'lokasi_prov' => Yii::t('rup', 'Lokasi Prov'),
            'statuspaketkonsolidasi' => Yii::t('rup', 'Statuspaketkonsolidasi'),
            'idsatker' => Yii::t('rup', 'Idsatker'),
            'tipe_swakelola' => Yii::t('rup', 'Tipe Swakelola'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'kode_klpd_penyelenggara' => Yii::t('rup', 'Kode Klpd Penyelenggara'),
            'nama_klpd_penyelenggara' => Yii::t('rup', 'Nama Klpd Penyelenggara'),
            'nama_satker_penyelenggara' => Yii::t('rup', 'Nama Satker Penyelenggara'),
            'sumberdana' => Yii::t('rup', 'Sumber Dana'),
        ];
    }
    // Daerah Start relation

    /**
     * Get created_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUserMaker()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Get updated_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUserUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Get updated_by relation from [[Satker]] table.
     *
     * @return \yii\db\ActiveQuery|SatkerQuery
     */
    public function getSatker()
    {
        return $this->hasOne(Satker::class, ['code' => 'kodesatker']);
    }

    /**
     * Get updated_by relation from [[Rupmetodepengadaan]] table.
     *
     * @return \yii\db\ActiveQuery|RupmetodepengadaanQuery
     */
    public function getRupmetodepengadaan()
    {
        return $this->hasOne(Rupmetodepengadaan::class, ['id' => 'idmetodepengadaan']);
    }

    /**
     * Get updated_by relation from [[Rupmetodepengadaan]] table.
     *
     * @return \yii\db\ActiveQuery|RupmetodepengadaanQuery
     */
    public function getRupjenispengadaan()
    {
        return $this->hasOne(Rupjenispengadaan::class, ['id' => 'idjenispengadaan']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return RupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RupQuery(get_called_class());
    }

    //Daerah Start Virtual Attribute

    /**
     * Virtual Attribute for Full Name
     *
     * @return string
     */
    public function getNamaLengkap()
    {
        return $this->name;
    }

    /**
     * Virtual Attribute for Created By Name
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getMaker()
    {
        $data = null;
        if ($this->userMaker) {
            $data = $this->userMaker->profile->name ? $this->userMaker->profile->name : $this->userMaker->username;
        }
        return $data;
    }

    /**
     * Virtual Attribute for Updated By Name
     *
     * @return \yii\db\ActiveQuery|UserTypeQuery
     */
    public function getUpdater()
    {
        $data = null;
        if ($this->userUpdater) {
            $data = $this->userUpdater->profile->name ? $this->userUpdater->profile->name : $this->userUpdater->username;
        }
        return $data;
    }

    //Daerah Akhir Virtual Attribute

    //Daerah Start Event Driven
    /**
     * Sebelum melakukan validasi input dan sebelum event beforeSave
     *
     * @return void
     */
    public function beforeValidate()
    {
        // Converting to db standard format
        // cth: $this->tanggal_mulai   = $this->tanggal_mulai ? Yii::$app->formatter->asDate($this->tanggal_mulai, 'yyyy-MM-dd') : null;

        parent::beforeValidate();
        return true;
    }

    /**
     * Sebelum disimpan, sesuaikan dnegan format db
     *
     * @param [type] $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            // tulis sini jika ini adalah new insert
        }
        // tulis sini saat update/insert

        if (!$this->is_sync) {
            if (isset($this->satker->name)) {
                $this->namasatker = $this->satker->name;
            }
            // if($this->idmetodepengadaan && isset(self::SELECT_METODE_PENGADAAN[$this->idmetodepengadaan])){
            //     $this->metodepengadaan = self::SELECT_METODE_PENGADAAN[$this->idmetodepengadaan];
            // }
            // if($this->idjenispengadaan && isset(self::SELECT_JENIS_PENGADAAN[$this->idjenispengadaan])){
            //     $this->jenispengadaan = self::SELECT_JENIS_PENGADAAN[$this->idjenispengadaan];
            // }
            if (isset($this->rupmetodepengadaan->name)) {
                $this->metodepengadaan = $this->rupmetodepengadaan->name;
            }
            if (isset($this->rupjenispengadaan->name)) {
                $this->jenispengadaan = $this->rupjenispengadaan->name;
            }
        }

        parent::beforeSave($insert);
        return true;
    }

    /**
     * Setelah query ditemukan/selesai dan akan ditampilkan ke view
     *
     * @return void
     */
    public function afterFind()
    {
        // monggo
        $this->id = $this->idrup;
        $this->name = $this->namapaket;
        $this->namecode = "(" . $this->idrup . ")" . " " . $this->namapaket;
        $this->namecodeannounced = "(" . $this->statusumumkan . ") " . " (" . $this->idrup . ")" . " " . $this->namapaket;
        parent::afterFind();
        return true;
    }

    /**
     * Sebelum hapus
     *
     * @return void
     */
    public function beforeDelete()
    {
        // monggo

        parent::beforeDelete();
        return true;
    }
    //Daerah Akhir Event Driven

}
