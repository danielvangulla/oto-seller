<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Kendaraan extends Model
{
    protected $collection = 'kendaraan';

    protected $fillable = [
        'tahun_keluaran',
        'warna',
        'harga',
        'qty',
    ];

    protected $hidden = [];


    public function motor()
    {
        return $this->hasOne(Motor::class, 'kendaraan_id');
    }

    public function mobil()
    {
        return $this->hasOne(Mobil::class, 'kendaraan_id');
    }
}
