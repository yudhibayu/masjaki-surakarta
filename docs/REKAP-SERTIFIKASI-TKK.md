# Rekap Sertifikasi Tenaga Kerja Konstruksi

## Struktur dan akses

Modul ini memakai Yii2 Basic, module `references-masjaki`, layout `admin_core`, dan izin RBAC `Administrator`. Menu **Rekap Sertifikasi TKK** membuka `/references-masjaki/tkk`. Dari dashboard tersedia halaman tenaga kerja, kegiatan, keikutsertaan, dan master LSP. Dokumen diunggah dari detail keikutsertaan.

Tabel baru:

| Tabel | Fungsi |
| --- | --- |
| `tenaga_kerja_konstruksi` | Identitas unik berdasarkan NIK |
| `master_lsp` | LSP aktif atau nonaktif |
| `kegiatan_fasilitasi_sertifikasi` | Kegiatan menurut tahun dan LSP |
| `peserta_fasilitasi_sertifikasi` | Riwayat keikutsertaan satu tenaga kerja pada satu kegiatan, dengan satu jabatan, skema, dan SKK |
| `dokumen_peserta_sertifikasi` | Banyak dokumen untuk satu keikutsertaan |

Semua relasi memakai foreign key `RESTRICT` saat penghapusan. Tidak tersedia penghapusan fisik tenaga kerja, kegiatan, atau keikutsertaan dari UI; LSP dapat dinonaktifkan. NIK ditampilkan tersamar pada daftar dan pencarian peserta, sedangkan detail hanya untuk Administrator.

Dashboard menghitung tenaga kerja unik dengan `COUNT(DISTINCT tenaga_kerja_id)`, keikutsertaan sebagai jumlah transaksi, dan SKK terbit hanya jika nomor dan tanggal terbit terisi. Semua kartu dan tabel mengikuti filter tahun. Kegiatan tanpa peserta tetap dihitung jika hanya filter kegiatan/tahun/LSP dipakai; ketika filter atribut peserta digunakan, yang dihitung adalah kegiatan dengan peserta yang cocok.

Jabatan kerja, jenjang, dan skema memakai teks karena repository belum mempunyai master yang sesuai. Isi nilai sebenarnya saat entri; jangan membuat daftar referensi fiktif.

## Dokumen

File tersimpan di `storage/tkk-sertifikasi` di luar `web`, dengan nama fisik acak. Format PDF, JPG/JPEG, PNG, maksimum 5 MB per file dan 10 file per unggahan. Aplikasi memeriksa ekstensi, MIME, dan isi file. Unduh hanya melalui route `/references-masjaki/tkk-dokumen/unduh?id=...` yang memeriksa izin Administrator. Pastikan proses PHP dapat menulis ke direktori `storage`; sesuaikan `upload_max_filesize` dan `post_max_size` PHP agar pengunggahan 5 MB dapat diterima. Cadangkan direktori `storage/tkk-sertifikasi` bersama database.

## Migration

File migration dasar: `migrations/m261021_000001_create_rekap_sertifikasi_tkk.php`. Halaman publik menambah migration `migrations/m261021_000002_add_tkk_kegiatan_publication.php` untuk kolom `is_public` pada kegiatan. Nilai awalnya `0`, termasuk untuk kegiatan lama, sehingga admin harus menerbitkan kegiatan secara sadar dari halaman detail kegiatan. Migration halaman publik belum dijalankan pada database aplikasi aktif atau produksi. Konfigurasi `yii` console pada repository ini memakai koneksi `config/db-production.php`, jadi pastikan target database dan cadangannya sebelum menjalankan perintah pada server.

Untuk menjalankan **hanya migration ini** pada lingkungan yang dituju, salin ke direktori migration sementara. Dari root aplikasi:

Windows PowerShell:

```powershell
New-Item -ItemType Directory -Path .\migrations-tkk -Force
Copy-Item .\migrations\m261021_000001_create_rekap_sertifikasi_tkk.php .\migrations-tkk\
Copy-Item .\migrations\m261021_000002_add_tkk_kegiatan_publication.php .\migrations-tkk\
php yii migrate/new '--migrationPath=@app/migrations-tkk'
php yii migrate/up 2 '--migrationPath=@app/migrations-tkk'
```

Linux:

```bash
mkdir -p migrations-tkk
cp migrations/m261021_000001_create_rekap_sertifikasi_tkk.php migrations-tkk/
cp migrations/m261021_000002_add_tkk_kegiatan_publication.php migrations-tkk/
php yii migrate/new --migrationPath=@app/migrations-tkk
php yii migrate/up 2 --migrationPath=@app/migrations-tkk
```

Angka `2` berlaku jika kedua migration masih pending. Jika migration dasar sudah dijalankan, gunakan `migrate/up 1` untuk migration publikasi saja. Baca daftar dari `migrate/new` sebelum menjalankan. Direktori `migrations-tkk` hanya untuk pemilihan migration; tidak dibutuhkan setelah proses selesai. Yii mencatat migration dalam `migration` table. Rollback migration dasar ditolak jika salah satu tabel modul sudah berisi data; rollback kolom publikasi ditolak jika masih ada kegiatan terbit.

## Halaman publik

URL `/rekap-sertifikasi-tkk` dapat dibuka tanpa login. Tautannya tersedia pada menu web umum `better_front` dan `standard_front`. Halaman utama menampilkan pilihan tahun dan empat kartu: tenaga kerja unik, keikutsertaan, peserta kompeten, dan SKK terbit. Angka hanya memakai kegiatan dengan `is_public=1`. Route detail `/rekap-sertifikasi-tkk/detail?id=...` masih tersedia untuk agregat kegiatan; kegiatan privat atau yang publikasinya dicabut menghasilkan 404. Admin menerbitkan atau mencabut publikasi melalui tombol pada detail kegiatan backoffice. NIK, nama peserta, kontak, alamat, dan dokumen tidak dikirim ke halaman publik. Kartu keikutsertaan menghitung record transaksi.

## Tes lokal

Tes integrasi membuat database MySQL sementara pada host lokal, menjalankan migration di sana, lalu menghapus database tes. Tes **tidak** menyentuh tabel aplikasi aktif. Jalankan hanya pada development dengan kredensial yang boleh membuat dan menghapus database:

```powershell
$env:TKK_TEST_DB='1'
php tests/integration/tkk_sertifikasi_smoke.php
Remove-Item Env:TKK_TEST_DB
```

Tes mencakup validasi NIK dan SKK, relasi historis, filter, hitungan dashboard, tampilan halaman, unggah beberapa dokumen, penolakan file yang isinya salah, otorisasi unduh, penghapusan dokumen, serta proteksi rollback. Sebelum penerapan produksi, uji ulang alur UI dengan akun Administrator pada lingkungan staging dan siapkan izin direktori serta backup.

Tes halaman publik dapat dijalankan tanpa MySQL dengan `php tests/integration/tkk_publik_sqlite_smoke.php`. Tes ini membuat database SQLite di memori untuk memeriksa default publikasi, filter, agregasi, akses tamu, dan pagination. Tetap jalankan tes MySQL penuh saat server lokal tersedia untuk memastikan migration dan query pada mesin database yang dipakai aplikasi.
