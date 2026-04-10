<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbKendaraan extends Model
{
    protected $table = 'tb_kendaraan';

    protected $fillable = ['plat_nomor', 'jenis_kendaraan', 'warna', 'pemilik', 'id_user'];

    public function user()
    {
        return $this->belongsTo(TbUser::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(TbTransaksi::class, 'id_kendaraan');
    }
}
