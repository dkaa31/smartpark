<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTarif extends Model
{
    protected $table = 'tb_tarif';

    protected $fillable = ['jenis_kendaraan', 'tarif_per_jam'];

    public function transaksi()
    {
        return $this->hasMany(TbTransaksi::class, 'id_tarif');
    }
}
