<?php

// Hanya untuk MySQL lokal: TKK_TEST_DB=1 php tests/integration/tkk_sertifikasi_smoke.php
if (getenv('TKK_TEST_DB') !== '1') {
    fwrite(STDERR, "Set TKK_TEST_DB=1 untuk memakai database test sementara.\n");
    exit(1);
}

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/yiisoft/yii2/Yii.php';

use app\components\TkkDokumenService;
use app\components\TkkDokumenStorage;
use app\components\TkkDateForm;
use app\components\TkkPublikRekap;
use app\modules\referencesMasjaki\models\DokumenPesertaSertifikasi;
use app\modules\referencesMasjaki\models\KegiatanFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\MasterLsp;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\TenagaKerjaKonstruksi;
use app\modules\referencesMasjaki\models\TkkDokumenForm;
use app\modules\referencesMasjaki\models\TkkReportFilter;
use app\modules\referencesMasjaki\models\TkkPublikFilter;
use yii\db\Connection;
use yii\web\UploadedFile;

class TkkTestUpload extends UploadedFile
{
    public function saveAs($file, $deleteTempFile = true)
    {
        return copy($this->tempName, $file);
    }
}

class TkkDeniedUser extends yii\web\User
{
    public function getIsGuest() { return false; }
    public function can($permissionName, $params = [], $allowCaching = true) { return false; }
}

class TkkAllowedUser extends TkkDeniedUser
{
    public function can($permissionName, $params = [], $allowCaching = true) { return $permissionName === 'Administrator'; }
}

class TkkGuestUser extends TkkDeniedUser
{
    public function getIsGuest() { return true; }
}

function tkkCheck($condition, $message)
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
$testDatabase = 'masjaki_tkk_test_' . bin2hex(random_bytes(5));
$adminDb->createCommand('CREATE DATABASE `' . $testDatabase . '` CHARACTER SET utf8mb4')->execute();
$testConfig = $databaseConfig;
$testConfig['dsn'] = preg_replace('/dbname=[^;]+/', 'dbname=' . $testDatabase, $databaseConfig['dsn']);
$trackedFiles = [];
$temporaryFiles = [];
$assetPath = dirname(__DIR__, 2) . '/runtime/tkk-test-assets';
yii\helpers\FileHelper::createDirectory($assetPath);
$_SERVER['REQUEST_URI'] = '/references-masjaki/tkk';

try {
    $app = new yii\console\Application([
        'id' => 'tkk-test', 'basePath' => dirname(__DIR__, 2),
        'params' => ['bsVersion' => '5.x', 'frontEndTheme' => 'better_front'],
        'aliases' => ['@webroot' => dirname(__DIR__, 2) . '/web', '@web' => '', '@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
        'modules' => ['references-masjaki' => ['class' => app\modules\referencesMasjaki\References::class, 'layout' => false]],
        'components' => [
            'db' => $testConfig,
            'user' => ['class' => yii\web\User::class, 'identityClass' => app\overrides\user\models\User::class, 'enableSession' => false],
            'request' => ['class' => yii\web\Request::class, 'cookieValidationKey' => 'tkk-test'],
            'response' => ['class' => yii\web\Response::class],
            'urlManager' => ['class' => yii\web\UrlManager::class, 'enablePrettyUrl' => true],
            'assetManager' => ['class' => yii\web\AssetManager::class, 'basePath' => $assetPath, 'baseUrl' => '/assets'],
        ],
    ]);
    require dirname(__DIR__, 2) . '/migrations/m261021_000001_create_rekap_sertifikasi_tkk.php';
    $migration = new m261021_000001_create_rekap_sertifikasi_tkk(['db' => $app->db]);
    $migration->safeUp();
    require dirname(__DIR__, 2) . '/migrations/m261021_000002_add_tkk_kegiatan_publication.php';
    $publicationMigration = new m261021_000002_add_tkk_kegiatan_publication(['db' => $app->db]);
    $publicationMigration->safeUp();

    $dateForm = new TenagaKerjaKonstruksi(['tanggal_lahir' => '29-02-2024']);
    TkkDateForm::normalize($dateForm, ['tanggal_lahir']);
    tkkCheck($dateForm->tanggal_lahir === '2024-02-29'
        && TkkDateForm::display($dateForm->tanggal_lahir) === '29-02-2024',
        'Tanggal formulir dikonversi dua arah');
    $dateForm->tanggal_lahir = '29-02-2025';
    tkkCheck(!TkkDateForm::normalize($dateForm, ['tanggal_lahir'])
        && $dateForm->hasErrors('tanggal_lahir') && $dateForm->tanggal_lahir === '29-02-2025',
        'Tanggal kalender tidak valid ditolak');
    $dateForm->clearErrors();
    $dateForm->tanggal_lahir = '2024-02-29';
    tkkCheck(!TkkDateForm::normalize($dateForm, ['tanggal_lahir']) && $dateForm->hasErrors('tanggal_lahir'),
        'Form menolak format selain dd-mm-yyyy');

    $person = new TenagaKerjaKonstruksi(['nik' => '3372010101900001', 'nama_lengkap' => 'Contoh Peserta', 'jenis_kelamin' => 'L']);
    tkkCheck($person->save(), 'Identitas tenaga kerja tersimpan');
    foreach ($person->getValidators('nik') as $validator) {
        if ($validator instanceof yii\validators\RegularExpressionValidator) {
            tkkCheck(yii\helpers\Html::escapeJsRegularExpression($validator->pattern) === '/^[0-9]{16}$/',
                'Pola NIK dapat divalidasi konsisten di browser dan server');
        }
    }
    $duplicate = new TenagaKerjaKonstruksi(['nik' => $person->nik, 'nama_lengkap' => 'Nama Lain']);
    tkkCheck(!$duplicate->save() && $duplicate->hasErrors('nik'), 'NIK duplikat ditolak');
    $invalidNik = new TenagaKerjaKonstruksi(['nik' => '1234', 'nama_lengkap' => 'Tidak Valid']);
    tkkCheck(!$invalidNik->validate() && $invalidNik->hasErrors('nik'), 'NIK harus 16 digit');
    $blankName = new TenagaKerjaKonstruksi(['nik' => '3372010101900003', 'nama_lengkap' => '   ']);
    tkkCheck(!$blankName->validate() && $blankName->hasErrors('nama_lengkap'), 'Nama kosong setelah dirapikan ditolak');
    $secondPerson = new TenagaKerjaKonstruksi(['nik' => '3372010101900002', 'nama_lengkap' => 'Peserta Kedua']);
    tkkCheck($secondPerson->save(), 'Identitas kedua tersimpan');

    $lsp = new MasterLsp(['nama_lsp' => 'LSP Contoh', 'is_active' => 1]);
    tkkCheck($lsp->save(), 'Master LSP tersimpan');
    $duplicateLsp = new MasterLsp(['nama_lsp' => 'LSP Contoh']);
    tkkCheck(!$duplicateLsp->save() && $duplicateLsp->hasErrors('nama_lsp'), 'Nama LSP duplikat ditolak');
    $lsp->is_active = 0;
    tkkCheck($lsp->save() && !MasterLsp::find()->where(['id' => $lsp->id, 'is_active' => 1])->exists(), 'LSP dapat dinonaktifkan');
    $lsp->is_active = 1;
    tkkCheck($lsp->save(), 'LSP dapat diaktifkan lagi');

    $invalidActivity = new KegiatanFasilitasiSertifikasi([
        'nama_kegiatan' => 'Tanggal Salah', 'tahun_anggaran' => 2026, 'tanggal_mulai' => '2026-11-20',
        'tanggal_selesai' => '2026-11-19', 'lsp_id' => $lsp->id,
    ]);
    tkkCheck(!$invalidActivity->validate() && $invalidActivity->hasErrors('tanggal_selesai'), 'Rentang tanggal kegiatan divalidasi');
    $activity2025 = new KegiatanFasilitasiSertifikasi([
        'nama_kegiatan' => 'Uji Tahun 2025', 'tahun_anggaran' => 2025,
        'tanggal_mulai' => '2025-06-01', 'tanggal_selesai' => '2025-06-02', 'lsp_id' => $lsp->id,
    ]);
    $activity2026 = new KegiatanFasilitasiSertifikasi([
        'nama_kegiatan' => 'Uji Tahun 2026', 'tahun_anggaran' => 2026,
        'tanggal_mulai' => '2026-06-01', 'tanggal_selesai' => '2026-06-02', 'lsp_id' => $lsp->id,
    ]);
    tkkCheck($activity2025->save() && $activity2026->save(), 'Kegiatan dua tahun tersimpan');
    tkkCheck(!$activity2025->is_public && !$activity2026->is_public,
        'Kegiatan baru tidak langsung terbit di web umum');

    $first = new PesertaFasilitasiSertifikasi([
        'tenaga_kerja_id' => $person->id, 'kegiatan_id' => $activity2025->id,
        'jabatan_kerja' => 'Jabatan Contoh', 'skema_sertifikasi' => 'Skema Contoh',
        'jenis_fasilitasi' => 'SERTIFIKASI_BARU', 'status_sertifikasi' => 'KOMPETEN',
    ]);
    tkkCheck($first->save() && $first->nomor_skk === null, 'Status kompeten tidak otomatis membuat SKK');
    $second = new PesertaFasilitasiSertifikasi([
        'tenaga_kerja_id' => $person->id, 'kegiatan_id' => $activity2026->id,
        'jabatan_kerja' => 'Jabatan Contoh', 'skema_sertifikasi' => 'Skema Contoh',
        'jenis_fasilitasi' => 'PERPANJANGAN', 'status_sertifikasi' => 'MENGIKUTI_UJI',
        'nomor_skk' => 'SKK-001', 'tanggal_terbit_skk' => '2026-07-01', 'masa_berlaku_skk_hingga' => '2029-07-01',
    ]);
    $third = new PesertaFasilitasiSertifikasi([
        'tenaga_kerja_id' => $secondPerson->id, 'kegiatan_id' => $activity2026->id,
        'jabatan_kerja' => 'Jabatan Kedua', 'skema_sertifikasi' => 'Skema Kedua',
        'jenis_fasilitasi' => 'SERTIFIKASI_BARU', 'status_sertifikasi' => 'BELUM_KOMPETEN',
    ]);
    tkkCheck($second->save() && $third->save(), 'Satu orang mengikuti tahun lain dan kegiatan memiliki banyak peserta');
    tkkCheck($person->getKeikutsertaan()->count() == 2 && $activity2026->getPeserta()->count() == 2,
        'Relasi satu-ke-banyak mempertahankan riwayat');
    $badStatus = new PesertaFasilitasiSertifikasi([
        'tenaga_kerja_id' => $person->id, 'kegiatan_id' => $activity2026->id,
        'jabatan_kerja' => 'Jabatan', 'skema_sertifikasi' => 'Skema',
        'jenis_fasilitasi' => 'SERTIFIKASI_BARU', 'status_sertifikasi' => 'LULUS',
    ]);
    tkkCheck(!$badStatus->validate() && $badStatus->hasErrors('status_sertifikasi'), 'Status di luar daftar ditolak');
    $badSkk = new PesertaFasilitasiSertifikasi([
        'tenaga_kerja_id' => $person->id, 'kegiatan_id' => $activity2026->id,
        'jabatan_kerja' => 'Jabatan', 'skema_sertifikasi' => 'Skema',
        'jenis_fasilitasi' => 'SERTIFIKASI_BARU', 'status_sertifikasi' => 'KOMPETEN', 'nomor_skk' => 'SKK-TANPA-TANGGAL',
    ]);
    tkkCheck(!$badSkk->validate() && $badSkk->hasErrors('nomor_skk'), 'Nomor SKK tanpa tanggal terbit ditolak');
    $badExpiry = new PesertaFasilitasiSertifikasi([
        'tenaga_kerja_id' => $person->id, 'kegiatan_id' => $activity2026->id,
        'jabatan_kerja' => 'Jabatan', 'skema_sertifikasi' => 'Skema',
        'jenis_fasilitasi' => 'SERTIFIKASI_BARU', 'status_sertifikasi' => 'KOMPETEN',
        'nomor_skk' => 'SKK-002', 'tanggal_terbit_skk' => '2026-07-01', 'masa_berlaku_skk_hingga' => '2026-06-01',
    ]);
    tkkCheck(!$badExpiry->validate() && $badExpiry->hasErrors('masa_berlaku_skk_hingga'),
        'Masa berlaku SKK sebelum tanggal terbit ditolak');

    $filter = new TkkReportFilter(['tahun' => 2026, 'lsp' => $lsp->id, 'status' => 'MENGIKUTI_UJI']);
    tkkCheck($filter->validate(), 'Filter laporan valid');
    $filtered = PesertaFasilitasiSertifikasi::find()->alias('p')
        ->innerJoin('{{%kegiatan_fasilitasi_sertifikasi}} k', 'k.id=p.kegiatan_id');
    $filter->apply($filtered);
    tkkCheck($filtered->count() == 1, 'Filter tahun, LSP, dan status diterapkan bersama');
    tkkCheck(PesertaFasilitasiSertifikasi::find()->count() == 3
        && PesertaFasilitasiSertifikasi::find()->select('tenaga_kerja_id')->distinct()->count() == 2,
        'Jumlah transaksi berbeda dari jumlah orang unik');

    $app->set('user', new TkkDeniedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $dashboard = new app\modules\referencesMasjaki\controllers\TkkController('tkk', $app);
    try {
        $dashboard->runAction('index');
        tkkCheck(false, 'Akun tanpa izin harus ditolak');
    } catch (yii\web\ForbiddenHttpException $ignored) {
        tkkCheck(true, 'Akses dashboard dibatasi Administrator');
    }
    $app->set('user', new TkkAllowedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $dashboardHtml = $app->getModule('references-masjaki')->runAction('tkk/index');
    tkkCheck((bool) preg_match('/data-tkk-stat="orang">2</', $dashboardHtml)
        && (bool) preg_match('/data-tkk-stat="keikutsertaan">3</', $dashboardHtml)
        && (bool) preg_match('/data-tkk-stat="skk">1</', $dashboardHtml),
        'Dashboard membedakan orang unik, transaksi, dan SKK terbit');
    $person->tanggal_lahir = '2024-02-29';
    tkkCheck($person->save(), 'Tanggal lahir tersimpan dalam format database');
    foreach ([
        'tkk-tenaga-kerja/update' => ['id' => $person->id, 'date' => '29-02-2024', 'fields' => 1],
        'tkk-kegiatan/update' => ['id' => $activity2026->id, 'date' => '01-06-2026', 'fields' => 2],
        'tkk-peserta/update' => ['id' => $second->id, 'date' => '01-07-2026', 'fields' => 2],
    ] as $route => $check) {
        $html = $app->getModule('references-masjaki')->runAction($route, ['id' => $check['id']]);
        tkkCheck(strpos($html, 'value="' . $check['date'] . '"') !== false
            && substr_count($html, 'placeholder="dd-mm-yyyy"') === $check['fields']
            && substr_count($html, 'input-group date') === $check['fields'],
            'Form edit ' . $route . ' menampilkan date picker dd-mm-yyyy');
    }
    foreach ([
        'tkk-tenaga-kerja/index', 'tkk-tenaga-kerja/create', 'tkk-tenaga-kerja/view' => ['id' => $person->id],
        'tkk-lsp/index', 'tkk-lsp/create', 'tkk-kegiatan/index', 'tkk-kegiatan/create',
        'tkk-kegiatan/view' => ['id' => $activity2026->id], 'tkk-peserta/index', 'tkk-peserta/create',
        'tkk-peserta/view' => ['id' => $second->id], 'tkk-dokumen/upload' => ['peserta_id' => $second->id],
    ] as $route => $params) {
        if (is_int($route)) { $route = $params; $params = []; }
        tkkCheck(is_string($app->getModule('references-masjaki')->runAction($route, $params)),
            'Halaman ' . $route . ' dapat dirender');
    }

    $publicEmpty = TkkPublikRekap::data(new TkkPublikFilter());
    tkkCheck(!$publicEmpty['rows'] && (int) $publicEmpty['stats']['keikutsertaan'] === 0,
        'Kegiatan yang belum diterbitkan tidak bocor ke rekap publik');
    $activity2026->is_public = 1;
    tkkCheck($activity2026->save(), 'Administrator dapat menerbitkan satu kegiatan');
    $publicReport = TkkPublikRekap::data(new TkkPublikFilter());
    tkkCheck((int) $publicReport['stats']['tenaga_kerja'] === 2
        && (int) $publicReport['stats']['keikutsertaan'] === 2
        && (int) $publicReport['stats']['skk'] === 1
        && (int) $publicReport['stats']['kegiatan'] === 1
        && count($publicReport['rows']) === 2,
        'Statistik dan tabel publik hanya memakai kegiatan terbit');
    $publicFilter = new TkkPublikFilter(['tahun' => 2026, 'jabatan' => 'Jabatan Contoh', 'skema' => 'Skema Contoh']);
    tkkCheck($publicFilter->validate(), 'Filter publik valid');
    $filteredPublic = TkkPublikRekap::data($publicFilter);
    tkkCheck(count($filteredPublic['rows']) === 1 && (int) $filteredPublic['stats']['keikutsertaan'] === 1,
        'Filter tahun, jabatan, dan skema mengubah tabel serta statistik');
    $privateYear = TkkPublikRekap::data(new TkkPublikFilter(['tahun' => 2025]));
    tkkCheck(!$privateYear['rows'] && (int) $privateYear['stats']['kegiatan'] === 0,
        'Filter tahun tetap menolak kegiatan privat');
    $app->set('user', new TkkGuestUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $publicController = new app\controllers\RekapSertifikasiTkkController('rekap-sertifikasi-tkk', $app);
    $publicController->layout = false;
    $publicHtml = $publicController->runAction('index');
    tkkCheck(str_contains($publicHtml, 'Rekap Data Tenaga Kerja Konstruksi')
        && !str_contains($publicHtml, $person->nik)
        && !str_contains($publicHtml, $person->nama_lengkap)
        && !str_contains($publicHtml, $activity2025->nama_kegiatan),
        'Halaman publik dapat dibuka tamu tanpa identitas pribadi atau kegiatan privat');
    $app->set('user', new TkkAllowedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));

    $pdf = tempnam(sys_get_temp_dir(), 'tkk-pdf-');
    file_put_contents($pdf, "%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\n%%EOF\n");
    $temporaryFiles[] = $pdf;
    $upload = function ($name) use ($pdf) {
        return new TkkTestUpload(['name' => $name, 'tempName' => $pdf, 'type' => 'application/pdf', 'size' => filesize($pdf), 'error' => UPLOAD_ERR_OK]);
    };
    $form = new TkkDokumenForm(['jenis_dokumen' => 'FORMULIR']);
    $form->files = [$upload('formulir.pdf'), $upload('lampiran.pdf')];
    tkkCheck((new TkkDokumenService())->upload($second, $form), 'Beberapa dokumen dapat diunggah sekaligus');
    $oversize = new TkkDokumenForm(['jenis_dokumen' => 'FORMULIR']);
    $oversize->files = [new TkkTestUpload([
        'name' => 'besar.pdf', 'tempName' => $pdf, 'type' => 'application/pdf',
        'size' => TkkDokumenForm::MAX_FILE_SIZE + 1, 'error' => UPLOAD_ERR_OK,
    ])];
    tkkCheck(!$oversize->validate() && $oversize->hasErrors('files'), 'File melebihi 5 MB ditolak');
    $documents = $second->getDokumen()->all();
    tkkCheck(count($documents) === 2, 'Relasi peserta memiliki banyak dokumen');
    foreach ($documents as $document) { $trackedFiles[] = TkkDokumenStorage::path($document->file_path); }
    $bad = tempnam(sys_get_temp_dir(), 'tkk-bad-');
    file_put_contents($bad, 'bukan PDF');
    $temporaryFiles[] = $bad;
    $badForm = new TkkDokumenForm(['jenis_dokumen' => 'KTP']);
    $badForm->files = [new TkkTestUpload(['name' => 'palsu.pdf', 'tempName' => $bad, 'type' => 'application/pdf', 'size' => filesize($bad), 'error' => UPLOAD_ERR_OK])];
    tkkCheck(!(new TkkDokumenService())->upload($second, $badForm) && $second->getDokumen()->count() == 2,
        'Isi file salah ditolak tanpa mengubah dokumen lama');
    tkkCheck(TkkDokumenStorage::download($documents[0]->id) instanceof yii\web\Response,
        'Dokumen dapat diunduh melalui endpoint berizin');
    $app->set('user', new TkkDeniedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    try {
        $app->getModule('references-masjaki')->runAction('tkk-dokumen/unduh', ['id' => $documents[0]->id]);
        tkkCheck(false, 'Unduh tanpa izin harus ditolak');
    } catch (yii\web\ForbiddenHttpException $ignored) {
        tkkCheck(true, 'Unduh dokumen pribadi ditolak tanpa izin');
    }
    $app->set('user', new TkkAllowedUser(['identityClass' => app\overrides\user\models\User::class, 'enableSession' => false]));
    $removedPath = TkkDokumenStorage::path($documents[0]->file_path);
    (new TkkDokumenService())->delete($documents[0]);
    tkkCheck(DokumenPesertaSertifikasi::findOne($documents[0]->id) === null && !is_file($removedPath),
        'Penghapusan metadata dan file dilakukan setelah transaksi');
    $migrationRejected = false;
    try { $migration->safeDown(); } catch (RuntimeException $ignored) { $migrationRejected = true; }
    tkkCheck($migrationRejected, 'Rollback ditolak saat data TKK sudah ada');
} finally {
    foreach ($trackedFiles as $path) { if (is_file($path)) { @unlink($path); } }
    foreach ($temporaryFiles as $path) { if (is_file($path)) { @unlink($path); } }
    if (isset($app)) { $app->db->close(); }
    $adminDb->createCommand('DROP DATABASE `' . $testDatabase . '`')->execute();
}
