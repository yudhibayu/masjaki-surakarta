<?php

// Tes baca-saja terhadap database SQLite sementara ketika MySQL lokal tidak tersedia.
require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/yiisoft/yii2/Yii.php';

use app\components\TkkPublikRekap;
use app\modules\referencesMasjaki\models\TkkPublikFilter;

function checkPublic($condition, $message)
{
    if (!$condition) { throw new RuntimeException($message); }
    echo "OK: {$message}\n";
}

$_SERVER['REQUEST_URI'] = '/rekap-sertifikasi-tkk';
$app = new yii\console\Application([
    'id' => 'tkk-publik-test', 'basePath' => dirname(__DIR__, 2),
    'aliases' => ['@webroot' => dirname(__DIR__, 2) . '/web', '@web' => '',
        '@bower' => '@vendor/bower-asset', '@npm' => '@vendor/npm-asset'],
    'params' => ['frontEndTheme' => 'better_front', 'bsVersion' => '5.x'],
    'components' => [
        'db' => ['class' => yii\db\Connection::class, 'dsn' => 'sqlite::memory:'],
        'request' => ['class' => yii\web\Request::class, 'cookieValidationKey' => 'tkk-publik-test'],
        'response' => ['class' => yii\web\Response::class],
        'urlManager' => ['class' => yii\web\UrlManager::class, 'enablePrettyUrl' => true],
        'user' => ['class' => yii\web\User::class,
            'identityClass' => app\overrides\user\models\User::class, 'enableSession' => false],
    ],
]);
$db = $app->db;
$db->createCommand('CREATE TABLE kegiatan_fasilitasi_sertifikasi (id INTEGER PRIMARY KEY, nama_kegiatan TEXT, tahun_anggaran INTEGER)')->execute();
$db->createCommand('CREATE TABLE peserta_fasilitasi_sertifikasi (id INTEGER PRIMARY KEY, kegiatan_id INTEGER, tenaga_kerja_id INTEGER, jabatan_kerja TEXT, jenjang_kualifikasi TEXT, skema_sertifikasi TEXT, status_sertifikasi TEXT, nomor_skk TEXT, tanggal_terbit_skk TEXT)')->execute();
$db->createCommand()->batchInsert('kegiatan_fasilitasi_sertifikasi',
    ['id', 'nama_kegiatan', 'tahun_anggaran'], [
        [1, 'Kegiatan Privat', 2025], [2, 'Kegiatan Publik', 2026], [3, 'Kegiatan Tanpa Peserta', 2027],
    ])->execute();
require dirname(__DIR__, 2) . '/migrations/m261021_000002_add_tkk_kegiatan_publication.php';
$migration = new m261021_000002_add_tkk_kegiatan_publication(['db' => $db]);
$migration->safeUp();
checkPublic((int) (new yii\db\Query())->from('kegiatan_fasilitasi_sertifikasi')->where(['is_public' => 1])->count() === 0,
    'Migration membuat semua kegiatan lama tidak terbit');
$db->createCommand()->update('kegiatan_fasilitasi_sertifikasi', ['is_public' => 1], ['id' => [2, 3]])->execute();
$db->createCommand()->batchInsert('peserta_fasilitasi_sertifikasi',
    ['id', 'kegiatan_id', 'tenaga_kerja_id', 'jabatan_kerja', 'jenjang_kualifikasi', 'skema_sertifikasi', 'status_sertifikasi', 'nomor_skk', 'tanggal_terbit_skk'], [
        [1, 1, 1, 'Rahasia', '1', 'Rahasia', 'KOMPETEN', 'PRIVATE', '2025-01-01'],
        [2, 2, 1, 'Pelaksana', '2', 'Skema A', 'KOMPETEN', 'SKK-1', '2026-01-01'],
        [3, 2, 2, 'Pengawas', '3', 'Skema B', 'BELUM_KOMPETEN', null, null],
    ])->execute();

$report = TkkPublikRekap::data(new TkkPublikFilter());
$summary = TkkPublikRekap::summary(new TkkPublikFilter());
checkPublic((int) $summary['stats']['tenaga_kerja'] === 2
    && (int) $summary['stats']['keikutsertaan'] === 2
    && (int) $summary['stats']['kompeten'] === 1
    && (int) $summary['stats']['skk'] === 1,
    'Empat kartu ringkasan mengambil angka dari kegiatan terbit');
checkPublic((int) TkkPublikRekap::summary(new TkkPublikFilter(['tahun' => 2027]))['stats']['keikutsertaan'] === 0,
    'Pilihan tahun mengubah angka kartu ringkasan');
checkPublic((int) $report['stats']['tenaga_kerja'] === 2
    && (int) $report['stats']['keikutsertaan'] === 2
    && (int) $report['stats']['kompeten'] === 1
    && (int) $report['stats']['belum_kompeten'] === 1
    && (int) $report['stats']['skk'] === 1
    && (int) $report['stats']['kegiatan'] === 2
    && count($report['rows']) === 2,
    'Statistik memakai kegiatan terbit dan menghitung kegiatan tanpa peserta');
checkPublic(!in_array('Rahasia', $report['jabatan'], true)
    && !in_array(2025, $report['years'], false), 'Pilihan filter tidak membocorkan kegiatan privat');

$filter = new TkkPublikFilter(['tahun' => 2026, 'jabatan' => 'Pelaksana', 'jenjang' => '2', 'skema' => 'Skema A']);
checkPublic($filter->validate(), 'Empat filter publik valid');
$filtered = TkkPublikRekap::data($filter);
checkPublic((int) $filtered['stats']['keikutsertaan'] === 1
    && (int) $filtered['stats']['kegiatan'] === 1
    && count($filtered['rows']) === 1, 'Filter mengubah statistik dan tabel');
checkPublic(!(new TkkPublikFilter(['tahun' => '2026 OR 1=1']))->validate(), 'Tahun tidak valid ditolak');

$resolved = $app->createController('rekap-sertifikasi-tkk/index');
checkPublic($resolved !== false && $resolved[0] instanceof app\controllers\RekapSertifikasiTkkController,
    'Route publik terhubung ke controller');
$controller = $resolved[0];
$controller->layout = false;
$html = $controller->runAction('index');
checkPublic(str_contains($html, 'Rekap Data Tenaga Kerja Konstruksi')
    && substr_count($html, 'data-tkk-public-stat=') === 4
    && str_contains($html, 'name="tahun"')
    && !str_contains($html, 'name="jabatan"')
    && !str_contains($html, '<table')
    && !str_contains($html, 'Kegiatan Privat')
    && !str_contains($html, 'Rahasia'), 'Tamu dapat melihat halaman tanpa data privat');
$detailHtml = $controller->runAction('detail', ['id' => 2]);
checkPublic(str_contains($detailHtml, 'Kegiatan Publik') && !str_contains($detailHtml, 'Rahasia'),
    'Detail kegiatan terbit hanya menampilkan agregat');
try {
    $controller->runAction('detail', ['id' => 1]);
    checkPublic(false, 'Detail kegiatan privat harus ditolak');
} catch (yii\web\NotFoundHttpException $ignored) {
    checkPublic(true, 'Detail kegiatan privat mengembalikan 404');
}
$db->createCommand()->update('kegiatan_fasilitasi_sertifikasi', ['is_public' => 0], ['id' => 2])->execute();
try {
    $controller->runAction('detail', ['id' => 2]);
    checkPublic(false, 'Detail kegiatan yang dicabut harus ditolak');
} catch (yii\web\NotFoundHttpException $ignored) {
    checkPublic(true, 'Pencabutan publikasi segera menutup detail kegiatan');
}
$db->createCommand()->update('kegiatan_fasilitasi_sertifikasi', ['is_public' => 1], ['id' => 2])->execute();

$extra = [];
for ($i = 4; $i <= 25; $i++) {
    $extra[] = [$i, 2, $i, 'Pelaksana', '2', 'Skema ' . $i, 'TERDAFTAR', null, null];
}
$db->createCommand()->batchInsert('peserta_fasilitasi_sertifikasi',
    ['id', 'kegiatan_id', 'tenaga_kerja_id', 'jabatan_kerja', 'jenjang_kualifikasi', 'skema_sertifikasi', 'status_sertifikasi', 'nomor_skk', 'tanggal_terbit_skk'],
    $extra)->execute();
$app->request->setQueryParams(['page' => 2]);
$page = TkkPublikRekap::data(new TkkPublikFilter());
checkPublic($page['pagination']->totalCount === 24 && count($page['rows']) === 4
    && $page['pagination']->offset === 20, 'Pagination menampilkan halaman kedua dari kelompok agregat');
