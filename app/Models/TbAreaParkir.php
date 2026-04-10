<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbAreaParkir extends Model
{
    protected $table = 'tb_area_parkir';

    protected $fillable = ['nama_area', 'kapasitas', 'terisi'];

    public function transaksi()
    {
        return $this->hasMany(TbTransaksi::class, 'id_area');
    }

    public function persentaseTerisi(): float
    {
        if ($this->kapasitas == 0) return 0;
        return round(($this->terisi / $this->kapasitas) * 100, 1);
    }
}
