<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasi';

    protected $fillable = [
        'nama',
        'no_hp',
        'tanggal',
        'waktu',
        'jumlah',
        'area',
        'user_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
