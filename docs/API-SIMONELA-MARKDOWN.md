
# Simonela v2.0

API Simonela diupdate agar kompatible dengan versi 2.0

<!--- If we have only one group/collection, then no need for the "ungrouped" heading -->



## Endpoints

* [Authorization](#authorization)
    1. [Get Token](#1-get-token)
        * [OK](#i-example-request-ok)
    1. [Get Refresh Token](#2-get-refresh-token)
        * [OK](#i-example-request-ok-1)
    1. [Revoke Token](#3-revoke-token)
        * [OK](#i-example-request-ok-2)
* [Resources](#resources)
    1. [Get Obyek Monev](#1-get-obyek-monev)
        * [OK](#i-example-request-ok-3)
        * [Unauthorized](#ii-example-request-unauthorized)
    1. [Get Monitoring Obyek](#2-get-monitoring-obyek)
        * [OK](#i-example-request-ok-4)
    1. [Get Monitoring Foto](#3-get-monitoring-foto)
        * [OK](#i-example-request-ok-5)

--------



## Authorization

Untuk melakukan autentikasi, refresh token, dan menghapus hak akses token.



### 1. Get Token


Me-request akses token yang bisa digunakan untuk mengambil resource monitoring


***Endpoint:***

```bash
Method: POST
Type: URLENCODED
URL: {{base_url}}/oauth2/rest/token
```



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| grant_type | password |  |
| client_id | testclient |  |
| client_secret | testpass |  |
| username | sadmin |  |
| password | admin123 |  |



***More example Requests/Responses:***


#### I. Example Request: OK



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| grant_type | password |  |
| client_id | testclient |  |
| client_secret | testpass |  |
| username | sadmin |  |
| password | admin123 |  |



#### I. Example Response: OK
```js
{
    "access_token": "49075da07b846739afbf5830bda2f0de62d8b4c3",
    "expires_in": 86400,
    "token_type": "Bearer",
    "scope": null,
    "refresh_token": "cc4eca46c18a96b2739093aee1a87a7a8f0a5ef3"
}
```


***Status Code:*** 200

<br>



### 2. Get Refresh Token


Mengambil refresh token jika token authentikasi sudah kadaluarsa


***Endpoint:***

```bash
Method: POST
Type: URLENCODED
URL: {{base_url}}/oauth2/rest/token
```



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| grant_type | refresh_token |  |
| client_id | testclient |  |
| client_secret | testpass |  |
| refresh_token | {{refresh_token}} |  |



***More example Requests/Responses:***


#### I. Example Request: OK



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| grant_type | refresh_token |  |
| client_id | testclient |  |
| client_secret | testpass |  |
| refresh_token |  |  |



#### I. Example Response: OK
```js
{
    "access_token": "c2f07667c8f96803894a16a506bac535f4b980f3",
    "expires_in": 86400,
    "token_type": "Bearer",
    "scope": null,
    "refresh_token": "99063bca870e61218b470502e157af5d57cf5819"
}
```


***Status Code:*** 200

<br>



### 3. Revoke Token


Menghapus hak akses token agar tidak bisa digunakan lagi


***Endpoint:***

```bash
Method: POST
Type: URLENCODED
URL: {{base_url}}/oauth2/rest/revoke
```



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| token | {{access_token}} |  |



***More example Requests/Responses:***


#### I. Example Request: OK



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| token |  |  |



#### I. Example Response: OK
```js
{
    "revoked": true
}
```


***Status Code:*** 200

<br>



## Resources

Menyediakan data API yang dapat digunakan di aplikasi lain sebagaimana mestinya



### 1. Get Obyek Monev


Memberikan data paket objek yang di monitoring dan evaluasi


***Endpoint:***

```bash
Method: POST
Type: URLENCODED
URL: {{base_url}}/api/default/get-obyek-monev
```



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| tahun | 2024 |  |



***More example Requests/Responses:***


#### I. Example Request: OK



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| tahun | 2024 |  |



#### I. Example Response: OK
```js
{
    "name": "Simonela Obyek Monev",
    "data": [
        {
            "tbllokasi_id": 13,
            "tbllokasi_pengadaan": "Pembangunan PSU Rumah Susun Mojo",
            "refskpd_nama": "Dinas Perumahan dan Kawasan Permukiman Serta Pertanahan (1.04.2.10.0.00.01.0000)",
            "refjenispengadaan_nama": "Pekerjaan Konstruksi",
            "refmetodepengadaan_nama": "Tender",
            "refobyek_nama": "Gedung",
            "refsumberdana_nama": "APBD",
            "tblkecamatan_nama": "Jebres",
            "tblkelurahan_nama": null,
            "tbllokasi_nobast": null,
            "tbllokasi_tglbast": null,
            "tbllokasi_latlang": "-7.584275513256777,110.82902551206763",
            "tbllokasi_program": "PROGRAM PEMBANGUNAN KAWASAN TRANSMIGRASI",
            "tbllokasi_kegiatan": "Penataan Persebaran Penduduk yang Berasal dari 1 (Satu) Daerah Kabupaten/Kota",
            "tbllokasi_pemenang": "PT. KARSA BANGUN PERKASA",
            "tbllokasi_tglpengadaanmulai": "2024-04-01",
            "tbllokasi_tglpengadaanselesai": "2024-05-01",
            "tbllokasi_tglpekerjaanmulai": "2024-06-01",
            "tbllokasi_tglpekerjaanselesai": "2024-09-01",
            "tbllokasi_paguanggaran": "1000000000.0000",
            "tbllokasi_hargapenawaran": "996000000",
            "tbllokasi_hargaperkiraan": "999000000",
            "tbllokasi_hargakontrak": "994000000",
            "tbllokasi_lokasi": "",
            "tbllokasi_nokontrak": "PG.01.02.9.2024",
            "tbllokasi_tglkontrak": "2024-02-28",
            "tbllokasi_nospmk": "PG.02.02.9.2024",
            "tbllokasi_tglspmk": "2024-02-27",
            "tbllokasi_volumepekerjaan": null,
            "tbllokasi_satuanpekerjaan": null,
            "tbllokasi_tglselesaipekerjaan": "2024-09-01",
            "tbllokasi_noselesaipekerjaan": null,
            "tbllokasi_bpjs_bayar": 1,
            "tbllokasi_bpjs_nomor": "20512603200",
            "tbllokasi_bpjs_iuran": "2500000.0000",
            "tbllokasi_bpjs_personel": 25,
            "tbllokasi_tahun": 2024,
            "tbllokasi_polygon": [
                "{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Point\",\"coordinates\":[110.829026,-7.584276]}}]}"
            ],
            "tbllokasi_polyline": ""
        },
        {
            "tbllokasi_id": 14,
            "tbllokasi_pengadaan": "Rekonstruksi Jalan Gotong Royong (DAK)",
            "refskpd_nama": "Dinas Pekerjaan Umum dan Penataan Ruang (1.03.0.00.0.00.01.0000)",
            "refjenispengadaan_nama": "Pekerjaan Konstruksi",
            "refmetodepengadaan_nama": "Tender",
            "refobyek_nama": "Jalan",
            "refsumberdana_nama": "APBD",
            "tblkecamatan_nama": "Pasar Kliwon",
            "tblkelurahan_nama": null,
            "tbllokasi_nobast": null,
            "tbllokasi_tglbast": null,
            "tbllokasi_latlang": "-7.37617715746959,111.76660920116475",
            "tbllokasi_program": "",
            "tbllokasi_kegiatan": "",
            "tbllokasi_pemenang": "PT. Manira Arta Rama Mandiri",
            "tbllokasi_tglpengadaanmulai": "2024-01-01",
            "tbllokasi_tglpengadaanselesai": "2024-03-01",
            "tbllokasi_tglpekerjaanmulai": "2024-03-01",
            "tbllokasi_tglpekerjaanselesai": "2024-11-01",
            "tbllokasi_paguanggaran": "2832463000.0000",
            "tbllokasi_hargapenawaran": "2797762000",
            "tbllokasi_hargaperkiraan": "2797762000",
            "tbllokasi_hargakontrak": "2797762000",
            "tbllokasi_lokasi": "",
            "tbllokasi_nokontrak": "KT09.12189.121",
            "tbllokasi_tglkontrak": "2024-04-01",
            "tbllokasi_nospmk": "KT09.12189.120",
            "tbllokasi_tglspmk": "2024-04-01",
            "tbllokasi_volumepekerjaan": null,
            "tbllokasi_satuanpekerjaan": null,
            "tbllokasi_tglselesaipekerjaan": null,
            "tbllokasi_noselesaipekerjaan": null,
            "tbllokasi_bpjs_bayar": null,
            "tbllokasi_bpjs_nomor": "",
            "tbllokasi_bpjs_iuran": null,
            "tbllokasi_bpjs_personel": null,
            "tbllokasi_tahun": 2024,
            "tbllokasi_polygon": [],
            "tbllokasi_polyline": ""
        }
    ]
}
```


***Status Code:*** 200

<br>



#### II. Example Request: Unauthorized



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| tahun | 2024 |  |



#### II. Example Response: Unauthorized
```js
<!DOCTYPE html>
<html lang="id_ID">
<div class="site-error">
    <section class="startup-expertise-area pt-120 pt-md-80 pt-sm-60 pb-114 pb-md-76 pb-sm-56">
        <div class="container">
            <div class="row">
                <!-- Start Section title -->
                <div class="col-lg-8 m-auto text-center">
                    <div class="section-title-wrap layout--2">
                        <h1>Unauthorized (#401)</h1>
                        <div class="alert alert-danger">
                            Your request was made with invalid credentials.                        </div>
                        <p>
                        Mohon maaf, terjadi kesalahan saat memproses permintaan anda.                        </p>
                        <p>
                        Mohon hubungi kami apabila menurut anda kesalahan ada pada server. Terima Kasih.                            
                        </p>
                    </div>
                </div>
                <!-- End Section title -->
            
            </div>
        </div>
    </section>
</div>
</html>
```


***Status Code:*** 401

<br>



### 2. Get Monitoring Obyek


Memberikan data monitoring berdasarkan id paket objek


***Endpoint:***

```bash
Method: POST
Type: URLENCODED
URL: {{base_url}}/api/default/get-monitoring-obyek
```



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| obyek_monev_id | 13 |  |



***More example Requests/Responses:***


#### I. Example Request: OK



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| obyek_monev_id | 13 |  |



#### I. Example Response: OK
```js
{
    "name": "Simonela Monitoring Monev",
    "data": [
        {
            "tblmonitoring_id": 9,
            "tbllokasi_id": 13,
            "refbulan_id": 3,
            "tblmonitoring_minggu": 1,
            "tblmonitoring_targetfisik": "3.0000",
            "tblmonitoring_realisasifisik": "3.0000",
            "tblmonitoring_she_item_identifikasi": "Tidak ada",
            "tblmonitoring_she_item_kejadian": "Tidak ada",
            "tblmonitoring_she_ringan": 0,
            "tblmonitoring_she_sedang": 0,
            "tblmonitoring_she_beratmd": 0,
            "refbulan_nama": "Maret"
        },
        {
            "tblmonitoring_id": 10,
            "tbllokasi_id": 13,
            "refbulan_id": 3,
            "tblmonitoring_minggu": 1,
            "tblmonitoring_targetfisik": "3.5000",
            "tblmonitoring_realisasifisik": "3.5000",
            "tblmonitoring_she_item_identifikasi": "",
            "tblmonitoring_she_item_kejadian": "",
            "tblmonitoring_she_ringan": 0,
            "tblmonitoring_she_sedang": 0,
            "tblmonitoring_she_beratmd": 0,
            "refbulan_nama": "Maret"
        },
        {
            "tblmonitoring_id": 11,
            "tbllokasi_id": 13,
            "refbulan_id": 3,
            "tblmonitoring_minggu": 1,
            "tblmonitoring_targetfisik": "4.0000",
            "tblmonitoring_realisasifisik": "4.0000",
            "tblmonitoring_she_item_identifikasi": "",
            "tblmonitoring_she_item_kejadian": "",
            "tblmonitoring_she_ringan": 0,
            "tblmonitoring_she_sedang": 0,
            "tblmonitoring_she_beratmd": 0,
            "refbulan_nama": "Maret"
        },
        {
            "tblmonitoring_id": 12,
            "tbllokasi_id": 13,
            "refbulan_id": 3,
            "tblmonitoring_minggu": 2,
            "tblmonitoring_targetfisik": "5.0000",
            "tblmonitoring_realisasifisik": "4.5000",
            "tblmonitoring_she_item_identifikasi": "",
            "tblmonitoring_she_item_kejadian": "",
            "tblmonitoring_she_ringan": 0,
            "tblmonitoring_she_sedang": 0,
            "tblmonitoring_she_beratmd": 0,
            "refbulan_nama": "Maret"
        },
        {
            "tblmonitoring_id": 14,
            "tbllokasi_id": 13,
            "refbulan_id": 4,
            "tblmonitoring_minggu": 3,
            "tblmonitoring_targetfisik": "7.0000",
            "tblmonitoring_realisasifisik": "7.5000",
            "tblmonitoring_she_item_identifikasi": "",
            "tblmonitoring_she_item_kejadian": "",
            "tblmonitoring_she_ringan": 0,
            "tblmonitoring_she_sedang": 0,
            "tblmonitoring_she_beratmd": 0,
            "refbulan_nama": "April"
        }
    ]
}
```


***Status Code:*** 200

<br>



### 3. Get Monitoring Foto


Mengambil data file dan gambar monitoring berdasarkan id monitoring objek


***Endpoint:***

```bash
Method: POST
Type: URLENCODED
URL: {{base_url}}/api/default/get-monitoring-foto
```



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| monitoring_id | 9 |  |



***More example Requests/Responses:***


#### I. Example Request: OK



***Body:***


| Key | Value | Description |
| --- | ------|-------------|
| monitoring_id | 9 |  |



#### I. Example Response: OK
```js
{
    "name": "Simonela Foto Monitoring",
    "data": [
        {
            "tblfotolokasi_id": 9,
            "tblmonitoring_id": 9,
            "tbllokasi_id": 13,
            "tblfotolokasi_caption": "",
            "tblfotolokasi_file": "https://simonela2.infinindotech.my.id/monitoring/pictures/progress/picture?id=jqmypvn427jd95m45rg3wlaxoe68z9"
        },
        {
            "tblfotolokasi_id": 11,
            "tblmonitoring_id": 9,
            "tbllokasi_id": 13,
            "tblfotolokasi_caption": "Pengecoran Jalan",
            "tblfotolokasi_file": "https://simonela2.infinindotech.my.id/monitoring/pictures/progress/picture?id=e46m9rewl87oxb7ybpqyd3ga2njzvg"
        }
    ]
}
```


***Status Code:*** 200

<br>



---
[Back to top](#simonela-v20)

>Generated at 2024-05-14 14:51:01 by [docgen](https://github.com/thedevsaddam/docgen)
