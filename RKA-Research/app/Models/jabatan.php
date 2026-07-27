<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class jabatan extends Model
{    
    protected $table = 'jabatan'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'jabatanID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string

    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        'jabatanID',
        'jabatan_code',
        'jabatan_name',
        'jabatan_type',
        'jabatan_level',
        'eselon',
    ];
}
