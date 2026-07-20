<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class satker extends Model
{   
    protected $table = 'satker'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'satkerID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string

    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        'satkerID',
        'satker_code',
        'satker_name',
        'satker_type',
        'status',
        'unitID',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
