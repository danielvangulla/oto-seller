<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Aplikasi Penjualan Kendaraan (Oto-Sales)

## Prerequisite

- [Composer](https://getcomposer.org/)
- [PHP 7.4++](https://www.php.net/)
- [MongoDB](https://www.mongodb.com/)



## Installation

- Jalankan perintah `composer install`

- Buat file `.env` pada root project.
```
cp .env.example .env
```

- Jalankan perintah `php artisan key:generate`
- Edit `DB_CONNECTION`, `DB_HOST`, dan `DB_DATABASE` pada `.env` sesuai dengan konfigurasi anda inginkan.
```
DB_CONNECTION=mongodb
DB_HOST=<string-connection-anda>
DB_DATABASE=<nama-database>
```

- Jalankan `php artisan test` untuk testing.





## API ROUTES

### REGISTER

- untuk Membuat User Baru.
```
URL : /api/register
Method: POST
Accept: application/json
Content-Type: application/json
Payload : {
    "name": required,
    "email": required,
    "password": required,
    "password_confirmation": required
}
```

### LOGIN


- untuk Login dan mendapatkan Token.
```
URL : /api/login
Method: POST
Accept: application/json
Content-Type: application/json
Payload : {
  "email": required,
  "password": required
}
```


### GET ALL DATA


- untuk Melihat semua data Mobil.
```
URL : /api/mobil
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```


- untuk Melihat semua data Motor.
```
URL : /api/motor
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```





### FIND DATA BY ID


- untuk mencari Data Mobil by ID.
```
URL : /api/mobil/{id}
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```


- untuk mencari Data Motor by ID.
```
URL : /api/motor/{id}
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```


- untuk mencari Data Kendaraan by ID.
```
URL : /api/kendaraan/{id}
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```



### CREATE NEW DATA


- untuk Create Data Mobil.
```
URL : /api/mobil
Method: POST
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "mesin": required,
    "kapasitas_penumpang": required,
    "tipe": required,
    "tahun_keluaran": required,
    "warna": required,
    "harga": required
}
```


- untuk Create Data Motor.
```
URL : /api/motor
Method: POST
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "mesin": required,
    "tipe_suspensi": required,
    "tipe_transmisi": required,
    "tahun_keluaran": required,
    "warna": required,
    "harga": required
}
```



### UPDATE DATA


- untuk Update Data Mobil.
```
URL : /api/mobil/{id}
Method: PATCH
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "mesin": required,
    "kapasitas_penumpang": required,
    "tipe": required,
    "tahun_keluaran": required,
    "warna": required,
    "harga": required
}
```


- untuk Update Data Motor.
```
URL : /api/motor/{id}
Method: PATCH
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "mesin": required,
    "tipe_suspensi": required,
    "tipe_transmisi": required,
    "tahun_keluaran": required,
    "warna": required,
    "harga": required
}
```



### DELETE DATA


- untuk menghapus Data Mobil.
```
URL : /api/mobil/{id}
Method: DELETE
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```


- untuk menghapus Data Motor.
```
URL : /api/motor/{id}
Method: DELETE
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```




### ADDING STOCK


- untuk melakukan Penambahan Stok Kendaraan.
```
URL : /api/add-stock/{kendaraan_id}
Method: PATCH
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "qty": required|numeric
}
```



### SALES (Decreased Stock)


- untuk melakukan Penjualan dengan Single Insert.
```
URL : /api/sales
Method: POST
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "data": {
        "kendaraan_id": required,
        "nama_pembeli": required,
        "catatan_lain": required,
        "nomor_rangka": required,
        "nomor_mesin": required
    }
}
```


- untuk melakukan Penjualan dengan Bulk Insert.
```
URL : /api/sales
Method: POST
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
Payload : {
    "is_bulk": true,
    "data": {
        "head": {
            "nama_pembeli": required,
            "catatan_lain": optional
        },
        "body": {
            {
                "kendaraan_id": required,
                "nomor_rangka": required,
                "nomor_mesin": required
            },
            {
                ....
            }
        }
    }
}
```




### REPORTS


- untuk melihat laporan Sisa Stok Kendaraan.
```
URL : /api/kendaraan
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```


- untuk melihat Laporan Penjualan By ID Kendaraan.
```
URL : /api/sales
Method: GET
Accept: application/json
Content-Type: application/json
Authorization: Bearer ...
```
