<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Motor extends Model
{
    protected $collection = 'motor';

    protected $fillable = [
        'mesin',
        'tipe_suspensi',
        'tipe_transmisi',
        'kendaraan_id',
    ];

    protected $hidden = [];


    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
