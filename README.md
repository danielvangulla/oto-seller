<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Aplikasi Penjualan Kendaraan (Oto-Sales)

## Prerequisite

- [Composer](https://getcomposer.org/)
- [PHP 7.4++](https://www.php.net/)
- [MongoDB](https://www.mongodb.com/)

## Installation

- Jalankan perintah `composer install`
- Jalankan perintah `php artisan key:generate`

- Buat file `.env` pada root project.
```
cp .env.example .env
```

- Edit `DB_CONNECTION`, `DB_HOST`, dan `DB_DATABASE` pada `.env` sesuai dengan konfigurasi anda inginkan.
```
DB_CONNECTION=mongodb
DB_HOST=<string-connection-anda>
DB_DATABASE=<nama-database>
```

- Jalankan `php artisan test` untuk testing.
