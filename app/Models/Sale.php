<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Sale extends Model
{
    protected $collection = 'sales';

    protected $fillable = [
        'kendaraan_id',
        'tanggal_jual',
        'user_id',
        'nama_pembeli',
        'catatan_lain',
        'nomor_rangka',
        'nomor_mesin',
    ];

    protected $hidden = [];

    protected $dates = [
        'tanggal_jual',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
