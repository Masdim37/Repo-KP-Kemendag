<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tor extends Model
{
    use HasFactory;

    protected $table = 'tor';
    protected $primaryKey = 'torID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    // Trik Otomatisasi JSON Laravel
    protected $casts = [
        'dasar_hukum'         => 'array',
        'penerima_manfaat'    => 'array',
        'tahapan_pelaksanaan' => 'array',
        'jadwal_pelaksanaan'  => 'array',
        'volume_ro'           => 'float',
        'total_biaya'         => 'float',
    ];
}