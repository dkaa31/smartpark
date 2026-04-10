<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TbUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_user';

    protected $fillable = [
        'nama_lengkap', 'username', 'password', 'role', 'status_aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function kendaraan()
    {
        return $this->hasMany(TbKendaraan::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(TbTransaksi::class, 'id_user');
    }

    public function logAktivitas()
    {
        return $this->hasMany(TbLogAktivitas::class, 'id_user');
    }
}
