# Sinkronisasi PBJ SIRUP terjadwal

Command Yii berikut menggunakan tahun berjalan di zona waktu Asia/Jakarta jika tahun tidak ditulis:

```powershell
php yii sirup-sync/tender
php yii sirup-sync/non-tender
php yii sirup-sync/run
```

Tambahkan argumen tahun untuk mengulang tahun tertentu, misalnya `php yii sirup-sync/run 2026`.
`tender` dan `non-tender` hanya memasukkan pekerjaan ke antrean; jalankan
`php yii queue/run --verbose=1` untuk memprosesnya. `run` memasukkan keduanya
dan memproses antrean sampai kosong, sehingga tidak membutuhkan worker tetap.

Pada server Linux, pasang cron Minggu pukul 05.00 WIB. Contoh berikut
mengasumsikan zona waktu server adalah `Asia/Jakarta`:

```cron
0 5 * * 0 cd /path/to/masjaki-surakarta-rebuild && /usr/bin/php yii sirup-sync/run
```

Ganti path proyek dan PHP dengan path di server. Pastikan database serta akses
ke SIRUP tersedia saat cron berjalan. Hasil sinkronisasi tercatat pada tabel
`sync_log`; error command dapat diarahkan ke log cron server.
