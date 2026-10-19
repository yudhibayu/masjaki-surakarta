<?php

// Run only on a local MySQL server: PRODUK_HUKUM_TEST_DB=1 php tests/integration/produk_hukum_smoke.php
if (getenv('PRODUK_HUKUM_TEST_DB') !== '1') {
    fwrite(STDERR, "Set PRODUK_HUKUM_TEST_DB=1 untuk database test sementara.\n");
    exit(1);
}

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/yiisoft/yii2/Yii.php';

use app\components\StandarHargaService;
use app\components\StandarHargaStorage;
use app\modules\referencesMasjaki\models\MasterProdukHukum;
use app\modules\referencesMasjaki\models\ProdukHukumForm;
use app\modules\referencesMasjaki\models\ProdukHukumTahunan;
use yii\db\Connection;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ProdukHukumTestFile extends UploadedFile
{
    public function saveAs($file, $deleteTempFile = true)
    {
        return copy($this->tempName, $file);
    }
}

class ProdukHukumDeniedUser extends yii\web\User
{
    public function getIsGuest() { return false; }
    public function can($permissionName, $params = [], $allowCaching = true) { return false; }
}

class ProdukHukumAllowedUser extends ProdukHukumDeniedUser
{
    public function can($permissionName, $params = [], $allowCaching = true) { return $permissionName === 'Administrator'; }
}

function checkProdukHukum($condition, $message)
{
    if (!$condition) { throw new RuntimeException($message); }
    echo "OK: {$message}\n";
}

$config = require dirname(__DIR__, 2) . '/config/db-production.php';
$dbConfig = $config['db'];
if (!preg_match('/\Amysql:host=(localhost|127\.0\.0\.1)(;|\z)/', $dbConfig['dsn'])) {
    throw new RuntimeException('Test hanya boleh berjalan pada MySQL lokal.');
}
$databaseName = 'masjaki_produk_hukum_test_' . bin2hex(random_bytes(5));
$adminConfig = $dbConfig;
unset($adminConfig['class']);
$adminDb = new Connection($adminConfig);
$adminDb->open();
$adminDb->createCommand('CREATE DATABASE `' . $databaseName . '` CHARACTER SET utf8mb4')->execute();
$testConfig = $dbConfig;
$testConfig['dsn'] = preg_replace('/dbname=[^;]+/', 'dbname=' . $databaseName, $dbConfig['dsn']);
$tracked = [];
$tempFiles = [];
$assetPath = dirname(__DIR__, 2) . '/runtime/produk-hukum-test-assets';
yii\helpers\FileHelper::createDirectory($assetPath);
$_SERVER['REQUEST_URI'] = '/references-masjaki/standar-harga';

try {
    $app = new yii\console\Application([
        'id' => 'produk-hukum-test', 'basePath' => dirname(__DIR__, 2), 'params' => ['frontEndTheme' => 'better_front'],
        'aliases' => ['@webroot' => dirname(__DIR__, 2) . '/web', '@web' => '', '@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
        'modules' => ['references-masjaki' => ['class' => app\modules\referencesMasjaki\References::class, 'layout' => false]],
        'components' => [
            'db' => $testConfig,
            'user' => ['class' => yii\web\User::class, 'identityClass' => app\overrides\user\models\User::class, 'enableSession' => false],
            'request' => ['class' => yii\web\Request::class, 'cookieValidationKey' => 'produk-hukum-test'],
            'response' => ['class' => yii\web\Response::class],
            'urlManager' => ['class' => yii\web\UrlManager::class, 'enablePrettyUrl' => true],
            'assetManager' => ['class' => yii\web\AssetManager::class, 'basePath' => $assetPath, 'baseUrl' => '/assets'],
        ],
    ]);
    require dirname(__DIR__, 2) . '/migrations/m261019_000001_create_standar_harga_tahunan.php';
    require dirname(__DIR__, 2) . '/migrations/m261019_000002_create_produk_hukum.php';
    require dirname(__DIR__, 2) . '/migrations/m261019_000003_migrate_standar_harga_data.php';
    require dirname(__DIR__, 2) . '/migrations/destructive/m261019_000004_drop_standar_harga_legacy.php';
    (new m261019_000001_create_standar_harga_tahunan(['db' => $app->db]))->safeUp();
    $when = date('Y-m-d H:i:s');
    $app->db->createCommand()->insert('{{%standar_harga_tahunan}}', ['tahun' => 2026, 'is_public' => 1, 'created_at' => $when, 'updated_at' => $when])->execute();
    $oldYearId = $app->db->getLastInsertID();
    $oldName = StandarHargaStorage::newName('pdf');
    $oldPath = StandarHargaStorage::directory() . DIRECTORY_SEPARATOR . $oldName;
    file_put_contents($oldPath, "%PDF-1.4\n" . str_repeat('x', 153600) . "\n%%EOF\n");
    $tracked[] = $oldPath;
    $app->db->createCommand()->insert('{{%standar_harga_dokumen}}', [
        'standar_harga_tahunan_id' => $oldYearId, 'jenis' => 'HSPK', 'nama_file' => 'HSPK lama.pdf',
        'file_path' => $oldName, 'mime_type' => 'application/pdf', 'file_size' => filesize($oldPath),
        'created_at' => $when, 'updated_at' => $when,
    ])->execute();
    (new m261019_000002_create_produk_hukum(['db' => $app->db]))->safeUp();
    $dropRejected = false;
    try {
        (new m261019_000004_drop_standar_harga_legacy(['db' => $app->db]))->safeUp();
    } catch (RuntimeException $ignored) {
        $dropRejected = true;
    }
    checkProdukHukum($dropRejected, 'Drop sebelum pemindahan ditolak');
    (new m261019_000003_migrate_standar_harga_data(['db' => $app->db]))->safeUp();
    $legacy = ProdukHukumTahunan::find()->where(['tahun' => 2026])->one();
    checkProdukHukum($legacy !== null && $legacy->master->nama === 'HSPK' && $legacy->is_public == 1, 'Dokumen lama dan status publikasi dipindahkan');
    checkProdukHukum($legacy->file_path !== $oldName && is_file(StandarHargaStorage::path($legacy->file_path)), 'File lama disalin tanpa menimpa sumber');
    $tracked[] = StandarHargaStorage::path($legacy->file_path);
    (new m261019_000003_migrate_standar_harga_data(['db' => $app->db]))->safeUp();
    checkProdukHukum(ProdukHukumTahunan::find()->count() == 1, 'Migration data idempotent');

    $hsd = MasterProdukHukum::findOne(['nama' => 'HSD']);
    $duplicate = new MasterProdukHukum(['nama' => 'HSD', 'is_active' => 1]);
    checkProdukHukum(!$duplicate->save() && $duplicate->hasErrors('nama'), 'Nama master unik');
    $blank = new MasterProdukHukum(['nama' => '   ', 'is_active' => 1]);
    checkProdukHukum(!$blank->save() && $blank->hasErrors('nama'), 'Nama master kosong ditolak');
    $custom = new MasterProdukHukum(['nama' => 'SBU', 'is_active' => 1]);
    checkProdukHukum($custom->save(), 'Master dapat ditambah');
    $custom->nama = 'SBU Konstruksi';
    checkProdukHukum($custom->save(), 'Nama master dapat diubah');
    $custom->is_active = 0;
    checkProdukHukum($custom->save(), 'Master dapat dinonaktifkan');
    checkProdukHukum(!MasterProdukHukum::find()->where(['is_active' => 1, 'id' => $custom->id])->exists(), 'Master nonaktif tidak masuk pilihan unggah');

    $pdf = tempnam(sys_get_temp_dir(), 'produk-hukum-');
    file_put_contents($pdf, "%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\n%%EOF\n");
    $tempFiles[] = $pdf;
    $upload = function ($name = 'baru.pdf') use ($pdf) {
        return new ProdukHukumTestFile(['name' => $name, 'tempName' => $pdf, 'type' => 'application/pdf', 'size' => filesize($pdf), 'error' => UPLOAD_ERR_OK]);
    };
    $form = new ProdukHukumForm(['master_produk_hukum_id' => $custom->id, 'tahun' => 2026]);
    $form->file = $upload();
    checkProdukHukum(!(new StandarHargaService())->save(new ProdukHukumTahunan(), $form), 'Master nonaktif menolak upload baru');
    $form = new ProdukHukumForm(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2026, 'is_public' => 0]);
    $form->file = $upload();
    checkProdukHukum((new StandarHargaService())->save(new ProdukHukumTahunan(), $form), 'Dokumen HSD dibuat');
    $first = ProdukHukumTahunan::findOne(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2026]);
    $firstId = $first->id;
    $firstPath = StandarHargaStorage::path($first->file_path);
    $tracked[] = $firstPath;
    $hsd->nama = 'HSD Terbaru';
    checkProdukHukum($hsd->save() && ProdukHukumTahunan::findOne($firstId)->master->nama === 'HSD Terbaru',
        'Perubahan nama master tercermin pada dokumen lama melalui foreign key');
    $form->is_public = 1;
    $form->file = $upload('pengganti.pdf');
    checkProdukHukum((new StandarHargaService())->save(new ProdukHukumTahunan(), $form), 'Upload ulang kombinasi sama berhasil');
    $replacement = ProdukHukumTahunan::findOne($firstId);
    checkProdukHukum($replacement->id === $firstId && $replacement->is_public == 0 && $replacement->file_path !== $first->file_path,
        'Upload ulang mengganti record sama tanpa menerbitkan otomatis');
    checkProdukHukum(!is_file($firstPath), 'File lama dihapus sesudah update berhasil');
    $tracked[] = StandarHargaStorage::path($replacement->file_path);
    $limitPath = tempnam(sys_get_temp_dir(), 'produk-hukum-limit-');
    file_put_contents($limitPath, '%PDF-' . str_repeat('x', ProdukHukumForm::MAX_FILE_SIZE - 5));
    $tempFiles[] = $limitPath;
    $limitForm = new ProdukHukumForm(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2026]);
    $limitForm->file = new ProdukHukumTestFile(['name' => 'tepat-2mb.pdf', 'tempName' => $limitPath, 'type' => 'application/pdf', 'size' => filesize($limitPath), 'error' => UPLOAD_ERR_OK]);
    checkProdukHukum($limitForm->validate(), 'File tepat 2 MB diterima');
    $largePath = tempnam(sys_get_temp_dir(), 'produk-hukum-large-');
    file_put_contents($largePath, '%PDF-' . str_repeat('x', ProdukHukumForm::MAX_FILE_SIZE));
    $tempFiles[] = $largePath;
    $largeForm = new ProdukHukumForm(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2026]);
    $largeForm->file = new ProdukHukumTestFile(['name' => 'besar.pdf', 'tempName' => $largePath, 'type' => 'application/pdf', 'size' => filesize($largePath), 'error' => UPLOAD_ERR_OK]);
    checkProdukHukum(!$largeForm->validate() && $largeForm->hasErrors('file'), 'File di atas 2 MB ditolak');
    try {
        StandarHargaStorage::send($replacement->id, false, true);
        checkProdukHukum(false, 'Dokumen draft harus ditolak');
    } catch (NotFoundHttpException $ignored) {
        checkProdukHukum(true, 'Endpoint publik menolak draft');
    }
    $bad = tempnam(sys_get_temp_dir(), 'produk-hukum-bad-');
    file_put_contents($bad, 'bukan pdf');
    $tempFiles[] = $bad;
    $badForm = new ProdukHukumForm(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2026, 'is_public' => 0]);
    $badForm->file = new ProdukHukumTestFile(['name' => 'rusak.pdf', 'tempName' => $bad, 'type' => 'application/pdf', 'size' => filesize($bad), 'error' => UPLOAD_ERR_OK]);
    checkProdukHukum(!(new StandarHargaService())->save(new ProdukHukumTahunan(), $badForm), 'Upload gagal ditolak');
    checkProdukHukum(ProdukHukumTahunan::findOne($firstId)->file_path === $replacement->file_path, 'Upload gagal tidak merusak file lama');
    $publish = new ProdukHukumForm(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2026, 'is_public' => 1]);
    checkProdukHukum((new StandarHargaService())->save($replacement, $publish, true)
        && ProdukHukumTahunan::findOne($firstId)->is_public == 1, 'Edit eksplisit dapat menerbitkan dokumen tanpa mengganti file');
    $replacement->is_public = 0;
    checkProdukHukum($replacement->save(), 'Publikasi dapat dicabut kembali');

    $form = new ProdukHukumForm(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2025, 'is_public' => 1]);
    $form->file = $upload();
    checkProdukHukum((new StandarHargaService())->save(new ProdukHukumTahunan(), $form), 'Satu master dapat memiliki dokumen tahun lain');
    $year2025 = ProdukHukumTahunan::findOne(['master_produk_hukum_id' => $hsd->id, 'tahun' => 2025]);
    $tracked[] = StandarHargaStorage::path($year2025->file_path);
    try {
        $app->db->createCommand()->insert('{{%produk_hukum_tahunan}}', [
            'master_produk_hukum_id' => $hsd->id, 'tahun' => 2025, 'nama_file' => 'duplikat.pdf',
            'file_path' => StandarHargaStorage::newName('pdf'), 'is_public' => 0,
            'created_at' => $when, 'updated_at' => $when,
        ])->execute();
        checkProdukHukum(false, 'Indeks unik harus menolak duplikat');
    } catch (yii\db\IntegrityException $ignored) {
        checkProdukHukum(true, 'Indeks unik kombinasi master dan tahun aktif');
    }
    $legacy->master->is_active = 0;
    checkProdukHukum($legacy->master->save(), 'Master dokumen lama dapat dinonaktifkan');
    $inactiveReplacement = new ProdukHukumForm(['master_produk_hukum_id' => $legacy->master_produk_hukum_id, 'tahun' => 2026, 'is_public' => 1]);
    $inactiveReplacement->file = $upload();
    checkProdukHukum(!(new StandarHargaService())->save($legacy, $inactiveReplacement, true), 'Master nonaktif menolak penggantian file');
    checkProdukHukum(StandarHargaStorage::send($legacy->id, true, true) instanceof yii\web\Response,
        'Dokumen publik tetap tersedia walau master nonaktif');
    $years = ProdukHukumTahunan::find()->select('tahun')->where(['is_public' => 1, 'deleted_at' => null])->distinct()->orderBy(['tahun' => SORT_DESC])->column();
    checkProdukHukum(array_map('intval', $years) === [2026, 2025], 'Filter tahun hanya dari dokumen publik dan terurut');
    $public = ProdukHukumTahunan::find()->where(['tahun' => 2026, 'is_public' => 1, 'deleted_at' => null])->with('master')->all();
    checkProdukHukum(count($public) === 1 && $public[0]->id == $legacy->id, 'Daftar publik tidak memuat draft');
    $html = $app->view->renderFile('@app/views/peraturan-jasa-konstruksi/index.php', ['years' => $years, 'selectedYear' => 2026, 'documents' => $public]);
    checkProdukHukum(strpos($html, 'HSPK') !== false && strpos($html, 'HSD') === false, 'Tampilan publik memuat master nonaktif yang publik');
    $publicController = new app\controllers\PeraturanJasaKonstruksiController('peraturan-jasa-konstruksi', $app);
    $publicController->layout = false;
    $page = $publicController->runAction('index', ['tahun' => 2026]);
    checkProdukHukum(strpos($page, 'HSPK') !== false, 'Route daftar publik dapat dirender');
    $legacy->is_public = 0;
    checkProdukHukum($legacy->save(), 'Publikasi dokumen lama dapat dicabut');
    try {
        StandarHargaStorage::send($legacy->id, false, true);
        checkProdukHukum(false, 'Dokumen dicabut harus ditolak');
    } catch (NotFoundHttpException $ignored) {
        checkProdukHukum(true, 'Endpoint menolak dokumen dicabut');
    }

    $adminController = new app\modules\referencesMasjaki\controllers\StandarHargaController('standar-harga', $app);
    checkProdukHukum($adminController->behaviors()['access']['rules'][0]['roles'] === ['Administrator'], 'CRUD hanya untuk Administrator');
    $app->set('user', new ProdukHukumDeniedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    try {
        $adminController->runAction('index');
        checkProdukHukum(false, 'Akun tanpa role harus ditolak');
    } catch (yii\web\ForbiddenHttpException $ignored) {
        checkProdukHukum(true, 'Akun tanpa role ditolak');
    }
    $app->set('user', new ProdukHukumAllowedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $adminHtml = $app->getModule('references-masjaki')->runAction('standar-harga/index');
    checkProdukHukum(strpos($adminHtml, 'Peraturan Jasa Konstruksi') !== false, 'Daftar backoffice dapat dirender');
    $masterHtml = $app->getModule('references-masjaki')->runAction('master-produk-hukum/index');
    checkProdukHukum(strpos($masterHtml, 'Master Produk Hukum') !== false, 'Daftar master dapat dirender');
    $createHtml = $app->getModule('references-masjaki')->runAction('standar-harga/create');
    checkProdukHukum(strpos($createHtml, 'SBU Konstruksi') === false && strpos($createHtml, 'Maksimum 2 MB') !== false,
        'Form unggah hanya menawarkan master aktif dan menampilkan batas file');
    checkProdukHukum((new StandarHargaService())->delete($replacement)
        && ProdukHukumTahunan::findOne(['id' => $firstId, 'deleted_at' => null]) === null,
        'Hapus dokumen menggunakan soft delete');

    (new m261019_000004_drop_standar_harga_legacy(['db' => $app->db]))->safeUp();
    checkProdukHukum($app->db->schema->getTableSchema('standar_harga_tahunan', true) === null, 'Migration destruktif hanya diuji pada DB sementara');
} finally {
    foreach ($tracked as $path) {
        if (is_file($path)) { @unlink($path); }
    }
    foreach ($tempFiles as $path) {
        if (is_file($path)) { @unlink($path); }
    }
    if (isset($app)) { $app->db->close(); }
    $adminDb->createCommand('DROP DATABASE `' . $databaseName . '`')->execute();
}
