<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Mobil extends Model
{
    protected $collection = 'mobil';

    protected $fillable = [
        'mesin',
        'kapasitas_penumpang',
        'tipe',
        'kendaraan_id',
    ];

    protected $hidden = [];


    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
