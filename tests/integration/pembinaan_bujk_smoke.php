<?php

// Hanya untuk MySQL lokal: PEMBINAAN_TEST_DB=1 php tests/integration/pembinaan_bujk_smoke.php
if (getenv('PEMBINAAN_TEST_DB') !== '1') {
    fwrite(STDERR, "Set PEMBINAAN_TEST_DB=1 untuk memakai database test sementara.\n");
    exit(1);
}

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/yiisoft/yii2/Yii.php';

use app\components\PembinaanDokumenService;
use app\components\PembinaanDokumenStorage;
use app\components\RekapPembinaan;
use app\controllers\RekapPembinaanController;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\referencesMasjaki\models\DokumenKegiatanPembinaan;
use app\modules\referencesMasjaki\models\HasilPembinaan;
use app\modules\referencesMasjaki\models\JenisKegiatanPembinaan;
use app\modules\referencesMasjaki\models\KegiatanPembinaan;
use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;
use app\modules\referencesMasjaki\models\PesertaPembinaan;
use yii\db\Connection;
use yii\web\UploadedFile;

class PembinaanTestUpload extends UploadedFile
{
    public function saveAs($file, $deleteTempFile = true)
    {
        return copy($this->tempName, $file);
    }
}

class PembinaanDeniedUser extends yii\web\User
{
    public function getIsGuest() { return false; }
    public function can($permissionName, $params = [], $allowCaching = true) { return false; }
}

class PembinaanAllowedUser extends PembinaanDeniedUser
{
    public function can($permissionName, $params = [], $allowCaching = true) { return $permissionName === 'Administrator'; }
}

function pembinaanCheck($condition, $message)
{
    if (!$condition) { throw new RuntimeException($message); }
    echo "OK: {$message}\n";
}

$databaseConfig = (require dirname(__DIR__, 2) . '/config/db-production.php')['db'];
if (!preg_match('/\Amysql:host=(localhost|127\.0\.0\.1)(;|\z)/', $databaseConfig['dsn'])) {
    throw new RuntimeException('Tes hanya boleh memakai server MySQL lokal.');
}
$adminConfig = $databaseConfig;
unset($adminConfig['class']);
$adminDb = new Connection($adminConfig);
$adminDb->open();
$existingBujk = $adminDb->getTableSchema('business_entity', true);
if ($existingBujk === null || !isset($existingBujk->columns['id']) || !$existingBujk->columns['id']->isPrimaryKey) {
    throw new RuntimeException('Tabel master BUJK business_entity.id tidak ditemukan pada database lokal.');
}
echo "Audit master BUJK: business_entity.id ({$existingBujk->columns['id']->dbType})\n";
$existingRelatedTables = array_values(array_filter($adminDb->schema->getTableNames(), function ($tableName) {
    return preg_match('/masyarakat_jasa_konstruksi|kegiatan_pembinaan|peserta_pembinaan/i', $tableName) === 1;
}));
echo 'Audit tabel masyarakat/pembinaan: ' . ($existingRelatedTables ? implode(', ', $existingRelatedTables) : 'belum ada') . "\n";
$testDatabase = 'masjaki_pembinaan_test_' . bin2hex(random_bytes(5));
$databaseCreated = false;
$testConfig = $databaseConfig;
$testConfig['dsn'] = preg_replace('/dbname=[^;]+/', 'dbname=' . $testDatabase, $databaseConfig['dsn']);
$testConnectionConfig = $testConfig;
unset($testConnectionConfig['class']);
$temporaryFiles = [];

try {
    $adminDb->createCommand('CREATE DATABASE `' . $testDatabase . '` CHARACTER SET utf8mb4')->execute();
    $databaseCreated = true;
    $testDb = new Connection($testConnectionConfig);
    $testDb->createCommand('CREATE TABLE business_entity (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(200) NOT NULL,
        account_verification INT NOT NULL DEFAULT 1,
        deleted_at DATETIME NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4')->execute();

    $assetPath = dirname(__DIR__, 2) . '/runtime/pembinaan-test-assets';
    yii\helpers\FileHelper::createDirectory($assetPath);
    $app = new yii\console\Application([
        'id' => 'pembinaan-test', 'basePath' => dirname(__DIR__, 2),
        'params' => ['bsVersion' => '5.x', 'frontEndTheme' => 'better_front'],
        'aliases' => ['@webroot' => dirname(__DIR__, 2) . '/web', '@web' => '', '@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
        'modules' => ['references-masjaki' => ['class' => app\modules\referencesMasjaki\References::class, 'layout' => false]],
        'components' => [
            'db' => $testConfig,
            'user' => ['class' => yii\web\User::class, 'identityClass' => app\overrides\user\models\User::class, 'enableSession' => false],
            'request' => ['class' => yii\web\Request::class, 'cookieValidationKey' => 'pembinaan-test'],
            'response' => ['class' => yii\web\Response::class],
            'urlManager' => ['class' => yii\web\UrlManager::class, 'enablePrettyUrl' => true],
            'assetManager' => ['class' => yii\web\AssetManager::class, 'basePath' => $assetPath, 'baseUrl' => '/assets'],
        ],
    ]);
    require dirname(__DIR__, 2) . '/migrations/m261023_000001_create_pembinaan_bujk.php';
    $migration = new m261023_000001_create_pembinaan_bujk(['db' => $app->db]);
    $migration->safeUp();

    $app->db->createCommand()->insert('business_entity', [
        'company_name' => 'BUJK Tes Integrasi', 'account_verification' => BusinessEntity::VERIFICATION,
    ])->execute();
    $bujkId = (int) $app->db->getLastInsertID();
    $app->db->createCommand()->insert('business_entity', [
        'company_name' => 'BUJK Kedua Uji', 'account_verification' => BusinessEntity::VERIFICATION,
    ])->execute();
    $otherBujkId = (int) $app->db->getLastInsertID();
    $type = new JenisKegiatanPembinaan(['nama' => 'Sosialisasi Uji']);
    pembinaanCheck($type->save(), 'Master jenis kegiatan dapat dibuat');
    $otherType = new JenisKegiatanPembinaan(['nama' => 'Bimbingan Teknis Uji']);
    pembinaanCheck($otherType->save(), 'Master jenis kegiatan kedua dapat dibuat');
    $person = new MasyarakatJasaKonstruksi([
        'jenis_subjek' => MasyarakatJasaKonstruksi::PERSEORANGAN,
        'nama' => 'Peserta Pribadi Uji', 'nik' => '3372010101900001',
    ]);
    pembinaanCheck($person->save(), 'Master masyarakat perseorangan dengan NIK valid dapat disimpan');
    $group = new MasyarakatJasaKonstruksi([
        'jenis_subjek' => MasyarakatJasaKonstruksi::KELOMPOK,
        'nama' => 'Kelompok Jasa Konstruksi Uji', 'nik' => '3372010101900002',
    ]);
    pembinaanCheck($group->save() && $group->nik === null, 'NIK kelompok dihapus dan tidak diwajibkan');

    $makeActivity = function ($year, $published, $name) use ($type) {
        $activity = new KegiatanPembinaan([
            'jenis_kegiatan_id' => $type->id, 'tahun_anggaran' => $year,
            'nama_kegiatan' => $name, 'is_published' => $published,
        ]);
        if (!$activity->save()) { throw new RuntimeException('Kegiatan test gagal disimpan.'); }
        return $activity;
    };
    $public2026 = $makeActivity(2026, 1, 'Kegiatan Publik 2026');
    $private2026 = $makeActivity(2026, 0, 'Kegiatan Draft 2026');
    $public2025 = $makeActivity(2025, 1, 'Kegiatan Publik 2025');
    $emptyPublic2024 = $makeActivity(2024, 1, 'Kegiatan Tanpa Peserta 2024');
    $otherTypeActivity = new KegiatanPembinaan([
        'jenis_kegiatan_id' => $otherType->id, 'tahun_anggaran' => 2025,
        'nama_kegiatan' => 'Kegiatan Publik Jenis Kedua', 'is_published' => 1,
    ]);
    pembinaanCheck($otherTypeActivity->save(), 'Kegiatan dapat dibuat pada jenis kegiatan yang berbeda');

    $bujkParticipant = new PesertaPembinaan([
        'kegiatan_id' => $public2026->id, 'jenis_entitas' => PesertaPembinaan::BUJK,
        'id_business_entity' => $bujkId,
    ]);
    pembinaanCheck($bujkParticipant->save(), 'Master BUJK existing dapat dipilih sebagai peserta');
    $duplicateBujk = new PesertaPembinaan([
        'kegiatan_id' => $public2026->id, 'jenis_entitas' => PesertaPembinaan::BUJK,
        'id_business_entity' => $bujkId,
    ]);
    pembinaanCheck(!$duplicateBujk->save() && $duplicateBujk->hasErrors('id_business_entity'), 'Peserta BUJK duplikat pada kegiatan yang sama ditolak');
    $societyParticipant = new PesertaPembinaan([
        'kegiatan_id' => $public2026->id, 'jenis_entitas' => PesertaPembinaan::KELOMPOK,
        'masyarakat_jasa_konstruksi_id' => $group->id,
    ]);
    pembinaanCheck($societyParticipant->save(), 'Master kelompok hanya dapat dipilih sebagai peserta kelompok');
    $wrongSocietyType = new PesertaPembinaan([
        'kegiatan_id' => $public2026->id, 'jenis_entitas' => PesertaPembinaan::MASYARAKAT,
        'masyarakat_jasa_konstruksi_id' => $group->id,
    ]);
    pembinaanCheck(!$wrongSocietyType->save() && $wrongSocietyType->hasErrors('masyarakat_jasa_konstruksi_id'),
        'Master kelompok tidak dapat dipilih sebagai peserta masyarakat perseorangan');
    $participantSearchController = new app\modules\referencesMasjaki\controllers\PesertaPembinaanController(
        'peserta-pembinaan', $app->getModule('references-masjaki')
    );
    $personSearch = $participantSearchController->actionCariMasyarakat('Uji', MasyarakatJasaKonstruksi::PERSEORANGAN)['results'];
    $groupSearch = $participantSearchController->actionCariMasyarakat('Uji', MasyarakatJasaKonstruksi::KELOMPOK)['results'];
    pembinaanCheck(count($personSearch) === 1 && (int) $personSearch[0]['id'] === (int) $person->id
        && count($groupSearch) === 1 && (int) $groupSearch[0]['id'] === (int) $group->id,
        'Pencarian peserta memisahkan masyarakat perseorangan dan kelompok');
    $oldYearParticipant = new PesertaPembinaan([
        'kegiatan_id' => $public2025->id, 'jenis_entitas' => PesertaPembinaan::BUJK,
        'id_business_entity' => $bujkId,
    ]);
    $privateParticipant = new PesertaPembinaan([
        'kegiatan_id' => $private2026->id, 'jenis_entitas' => PesertaPembinaan::MASYARAKAT,
        'masyarakat_jasa_konstruksi_id' => $person->id,
    ]);
    pembinaanCheck($oldYearParticipant->save() && $privateParticipant->save(), 'Relasi peserta pada kegiatan lain dapat disimpan');

    $duplicateResult = new HasilPembinaan(['peserta_pembinaan_id' => $bujkParticipant->id, 'hasil_capaian' => 'Lulus']);
    pembinaanCheck($duplicateResult->save(), 'Hasil dapat dicatat per peserta');
    $secondResult = new HasilPembinaan(['peserta_pembinaan_id' => $bujkParticipant->id, 'hasil_capaian' => 'Duplikat']);
    pembinaanCheck(!$secondResult->save(), 'Satu peserta hanya memiliki satu hasil pembinaan');

    $invalidEntityRejected = false;
    try {
        $app->db->createCommand()->insert('peserta_pembinaan', [
            'kegiatan_id' => $public2026->id, 'id_business_entity' => null,
            'masyarakat_jasa_konstruksi_id' => null, 'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ])->execute();
    } catch (yii\db\Exception $ignored) {
        $invalidEntityRejected = true;
    }
    pembinaanCheck($invalidEntityRejected, 'Database menolak peserta tanpa tepat satu master');
    $bothEntitiesRejected = false;
    try {
        $app->db->createCommand()->insert('peserta_pembinaan', [
            'kegiatan_id' => $public2026->id, 'id_business_entity' => $otherBujkId,
            'masyarakat_jasa_konstruksi_id' => $group->id, 'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ])->execute();
    } catch (yii\db\Exception $ignored) {
        $bothEntitiesRejected = true;
    }
    pembinaanCheck($bothEntitiesRejected, 'Database menolak baris dengan dua master peserta sekaligus');

    $publicYear = RekapPembinaan::summary(2026, true)['stats'];
    pembinaanCheck($publicYear['bujk'] === 1 && $publicYear['masyarakat'] === 1
        && $publicYear['kegiatan'] === 1 && $publicYear['keikutsertaan'] === 2,
        'Filter tahun menghitung entitas unik dan keikutsertaan serta mengecualikan draft');
    $allPublic = RekapPembinaan::summary(null, true);
    pembinaanCheck($allPublic['stats']['bujk'] === 1 && $allPublic['stats']['masyarakat'] === 1
        && $allPublic['stats']['kegiatan'] === 4 && $allPublic['stats']['keikutsertaan'] === 3,
        'Kegiatan kosong ikut dihitung tanpa menggandakan jumlah peserta unik');
    pembinaanCheck(count($allPublic['typeRows']) === 2 && $allPublic['typeRows'][0]['jenis_kegiatan'] === 'Bimbingan Teknis Uji'
        && (int) $allPublic['typeRows'][0]['kegiatan'] === 1,
        'Rekap per jenis kegiatan menghitung kegiatan yang sudah dipublikasikan');
    $filteredTypeReport = RekapPembinaan::summary(null, true, $type->id);
    pembinaanCheck($filteredTypeReport['stats']['kegiatan'] === 3 && count($filteredTypeReport['typeRows']) === 1
        && $filteredTypeReport['typeRows'][0]['jenis_kegiatan'] === 'Sosialisasi Uji',
        'Filter jenis kegiatan membatasi statistik dan rekap jenis');
    $internal = RekapPembinaan::summary(2026, false)['stats'];
    pembinaanCheck($internal['kegiatan'] === 2 && $internal['keikutsertaan'] === 3,
        'Rekap internal dapat mencakup kegiatan draft');

    $app->set('user', new PembinaanDeniedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    try {
        $adminController = new app\modules\referencesMasjaki\controllers\PembinaanController('pembinaan', $app);
        $adminController->layout = false;
        $adminController->runAction('index');
        pembinaanCheck(false, 'Back office wajib menolak pengguna tanpa Administrator');
    } catch (yii\web\ForbiddenHttpException $ignored) {
        pembinaanCheck(true, 'Akses back office dibatasi untuk Administrator');
    }
    $app->set('user', new PembinaanAllowedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));

    $referencesModule = $app->getModule('references-masjaki');
    $dashboardController = new app\modules\referencesMasjaki\controllers\PembinaanController('pembinaan', $referencesModule);
    $dashboardController->layout = false;
    $dashboardHtml = $dashboardController->runAction('index', ['tahun' => '', 'jenis' => '']);
    pembinaanCheck(strpos($dashboardHtml, 'Rekap per Jenis Kegiatan') !== false
        && strpos($dashboardHtml, 'Rekapitulasi Pembinaan') !== false
        && strpos($dashboardHtml, '/references-masjaki/rekap-pembinaan-admin') === false,
        'Dashboard menampilkan rekap di bawah sub menu tanpa tombol rekap terpisah');

    $adminReportController = new app\modules\referencesMasjaki\controllers\RekapPembinaanAdminController('rekap-pembinaan-admin', $referencesModule);
    $adminReportController->layout = false;
    $app->controller = $adminReportController;
    $adminReportHtml = $adminReportController->runAction('index', ['tahun' => '', 'jenis' => '']);
    pembinaanCheck(strpos($adminReportHtml, 'Rekap per Jenis Kegiatan') !== false,
        'Halaman laporan admin menampilkan rekap per jenis dan menerima filter kosong');

    $app->set('user', new yii\web\User(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $publicController = new RekapPembinaanController('rekap-pembinaan', $app);
    $publicController->layout = false;
    $publicHtml = $publicController->runAction('index', ['tahun' => '']);
    pembinaanCheck(strpos($publicHtml, 'BUJK dibina') !== false
        && strpos($publicHtml, 'BUJK Tes Integrasi') === false
        && strpos($publicHtml, '3372010101900001') === false
        && strpos($publicHtml, 'Peserta Pribadi Uji') === false,
        'Halaman publik menampilkan rekap agregat tanpa nama dan NIK');

    $pdf = tempnam(sys_get_temp_dir(), 'pembinaan-pdf-');
    file_put_contents($pdf, "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF");
    $temporaryFiles[] = $pdf;
    $document = new DokumenKegiatanPembinaan([
        'kegiatan_id' => $public2026->id, 'jenis_dokumen' => 'LAPORAN',
        'upload' => new PembinaanTestUpload(['name' => 'laporan.pdf', 'tempName' => $pdf, 'type' => 'application/pdf', 'size' => filesize($pdf), 'error' => UPLOAD_ERR_OK]),
    ]);
    pembinaanCheck((new PembinaanDokumenService())->upload($document), 'Dokumen kegiatan dapat diunggah melalui penyimpanan privat');
    $savedPath = PembinaanDokumenStorage::path($document->file_path);
    pembinaanCheck(strpos($savedPath, Yii::getAlias('@webroot')) !== 0, 'File dokumen berada di luar webroot');
    $documentId = $document->id;
    $app->set('user', new PembinaanDeniedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    try {
        $documentController = new app\modules\referencesMasjaki\controllers\DokumenPembinaanController('dokumen-pembinaan', $app);
        $documentController->runAction('download', ['id' => $documentId]);
        pembinaanCheck(false, 'Akses dokumen back office harus ditolak tanpa Administrator');
    } catch (yii\web\ForbiddenHttpException $ignored) {
        pembinaanCheck(true, 'Unduh dokumen dibatasi untuk Administrator');
    }
    $app->set('user', new PembinaanAllowedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $documentController = new app\modules\referencesMasjaki\controllers\DokumenPembinaanController('dokumen-pembinaan', $referencesModule);
    $app->controller = $documentController;
    $downloadResponse = $documentController->runAction('download', ['id' => $documentId]);
    pembinaanCheck($downloadResponse instanceof yii\web\Response
        && $downloadResponse->headers->get('X-Content-Type-Options') === 'nosniff',
        'Administrator dapat mengunduh file dengan header keamanan');
    (new PembinaanDokumenService())->delete($document);
    pembinaanCheck(DokumenKegiatanPembinaan::findOne($documentId) === null && !is_file($savedPath), 'Penghapusan dokumen menghapus metadata dan file privat');
} finally {
    if (isset($app)) { $app->db->close(); }
    if (is_file($pdf ?? '')) { @unlink($pdf); }
    if (is_file($savedPath ?? '')) { @unlink($savedPath); }
    if ($databaseCreated) {
        $adminDb->createCommand('DROP DATABASE `' . $testDatabase . '`')->execute();
    }
    $adminDb->close();
}
