# Peraturan Jasa Konstruksi

Web umum: `/peraturan-jasa-konstruksi`. Backoffice dokumen:
`/references-masjaki/standar-harga`. Backoffice master:
`/references-masjaki/master-produk-hukum`. CRUD backoffice hanya untuk role
`Administrator`.

## Struktur dan data lama

`m261019_000002_create_produk_hukum` membuat `master_produk_hukum` dan
`produk_hukum_tahunan`. Kombinasi `(master_produk_hukum_id, tahun)` unik,
termasuk setelah soft delete, agar unggah ulang memakai record yang sama.
`m261019_000003_migrate_standar_harga_data` menambahkan HSD, AHSP, HSPK dan
menyalin setiap dokumen lama ke nama file fisik baru. Salinan lama tetap ada.
Migration ini dapat diulang tanpa membuat baris ganda berdasarkan
`legacy_dokumen_id`. Ia berhenti bila menemukan arsip soft
delete, tahun tanpa dokumen, konflik kombinasi, atau file lama yang hilang.
Kasus tersebut harus ditinjau manual sebelum penghapusan tabel lama.

Audit lokal pada 19 Oktober 2026: migration lama sudah tercatat. Ada 1 baris
`standar_harga_tahunan` (2026, publik) dan 1 baris
`standar_harga_dokumen` (HSPK, publik). File fisiknya ada. Ukurannya
1.672.336 byte. Migration data mempertahankannya. Ini adalah kondisi database lokal yang diperiksa,
bukan jaminan isi database server Linux.

Migration penghapusan tabel lama berada di
`migrations/destructive/m261019_000004_drop_standar_harga_legacy.php`.
Lokasinya sengaja di luar direktori migration default sehingga tidak ikut
`php yii migrate/up`. Sebelum drop, migration memeriksa jumlah dokumen
yang dipindahkan, tahun tanpa dokumen, dan foreign key dari tabel lain.
**DROP TABLE bersifat destruktif.** `safeDown()` menolak rollback palsu:
data tabel lama hanya bisa dipulihkan dari backup database. Simpan backup
database dan direktori `storage/standar-harga` sebelum menjalankannya.
Jangan jalankan migration penghapusan pada production sebelum hasil migrasi
data dan kebutuhan arsip ditinjau.

## Menjalankan pada development

Pastikan `config/db-production.php` menunjuk ke **database development**
sebelum menggunakan command `php yii` pada checkout ini. Konfigurasi console
memakai file tersebut. Karena ada migration lain yang masih pending,
isolasi dua migration baru dalam direktori sementara:

```bash
cd /path/to/masjaki-surakarta-rebuild
tmpdir="$(mktemp -d)"
cp migrations/m261019_000002_create_produk_hukum.php "$tmpdir/"
cp migrations/m261019_000003_migrate_standar_harga_data.php "$tmpdir/"
php yii migrate/new --migrationPath="$tmpdir"
php yii migrate/up 2 --migrationPath="$tmpdir"
```

Daftar `migrate/new` harus menampilkan hanya dua migration di atas dalam
urutan 000002 lalu 000003. Jangan menjalankan migration 000003 jika file
lama belum tersedia di server. Setelah berhasil:

```bash
rm "$tmpdir/m261019_000002_create_produk_hukum.php" "$tmpdir/m261019_000003_migrate_standar_harga_data.php"
rmdir "$tmpdir"
```

Jalankan penghapusan lama secara **terpisah** hanya setelah backup dan audit:

```bash
php yii migrate/new --migrationPath='@app/migrations/destructive'
php yii migrate/up 1 --migrationPath='@app/migrations/destructive'
```

Migration penghapusan tidak dijalankan oleh implementasi ini pada database
aktif. Perintah di atas adalah petunjuk untuk tahap deploy yang diputuskan
secara terpisah.

## File, publikasi, dan akses

Unggahan baru menerima PDF, XLS, XLSX hingga 2 MB per file. File berada di
`storage/standar-harga` dengan nama acak. Endpoint publik hanya melayani
record yang belum dihapus, memiliki master valid, dan `is_public = 1`.
Master nonaktif tidak dapat digunakan untuk unggahan baru, tetapi dokumen
publik lamanya tetap dapat dilihat. PDF bisa dilihat di browser; semua
format bisa diunduh.

Pada server, atur PHP `upload_max_filesize` minimal `2M` dan
`post_max_size` lebih besar dari `2M` agar unggahan dapat mencapai batas
aplikasi. Konfigurasi PHP web server dapat berbeda dari PHP CLI.

Pada susunan Laragon saat ini, document root berada satu tingkat di atas
aplikasi. `storage/.htaccess` menolak akses langsung pada Apache. Jika server
Linux memakai Nginx atau Apache mengabaikan `.htaccess`, buat aturan server
yang menolak akses HTTP ke `/masjaki-surakarta-rebuild/storage/` sebelum
unggahan digunakan.

## Pengujian

Skrip integrasi hanya dapat berjalan pada MySQL lokal dan membuat database
sementara yang dibuang setelah tes:

```bash
PRODUK_HUKUM_TEST_DB=1 php tests/integration/produk_hukum_smoke.php
```

Skrip menguji migration, salinan file lama, aturan master, unggah ulang,
publikasi, akses endpoint, serta migration penghapusan **hanya pada database
sementara**.
