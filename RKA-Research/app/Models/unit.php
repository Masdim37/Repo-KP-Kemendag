<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class unit extends Model
{
    protected $table = 'unit'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'unitID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string

    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        'unitID',
        'unit_code',
        'unit_name',
        'parentUnitID',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
