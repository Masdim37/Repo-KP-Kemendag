<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    use HasFactory;

    protected $table = 'rab';
    protected $primaryKey = 'rabID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    // Mengubah JSON otomatis menjadi Array PHP dan sebaliknya
    protected $casts = [
        'faktor_perhitungan' => 'array',
        'volume_ro'          => 'float',
        'alokasi_dana'         => 'float',
        'volume'             => 'float',
        'harga_satuan'       => 'float',
        'jumlah_biaya'       => 'float',
    ];
}