<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTransaksi extends Model
{
    protected $table = 'tb_transaksi';

    protected $fillable = [
        'id_kendaraan', 'waktu_masuk', 'waktu_keluar',
        'id_tarif', 'durasi_jam', 'biaya_total', 'status', 'id_user', 'id_area',
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(TbKendaraan::class, 'id_kendaraan');
    }

    public function tarif()
    {
        return $this->belongsTo(TbTarif::class, 'id_tarif');
    }

    public function user()
    {
        return $this->belongsTo(TbUser::class, 'id_user');
    }

    public function area()
    {
        return $this->belongsTo(TbAreaParkir::class, 'id_area');
    }
}
