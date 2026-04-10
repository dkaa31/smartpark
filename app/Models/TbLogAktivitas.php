<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbLogAktivitas extends Model
{
    protected $table = 'tb_log_aktivitas';

    protected $fillable = ['id_user', 'aktivitas', 'waktu_aktivitas'];

    protected $casts = [
        'waktu_aktivitas' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(TbUser::class, 'id_user');
    }
}
