<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Database\Factories\UserFactory;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    
    protected $table = 'users'; //nama tabel memakai huruf kecil
    protected $primaryKey = 'userID'; //primary key diawali dengan nama tabelnya + ID dan bertipe data string 
    protected $keyType = 'string'; //tipe data primary key selalu string
    public $incrementing = false; //incrementing false karena primary key bertipe data string

    protected $fillable = [ //atribut diawali huruf kecil pada kata pertama dan diawali huruf besar pada kata kedua dan selanjutnya (jika ada)
        'userID',
        'username',
        'password',
        'name',
        'nip',
        'email',
        'status',
        'jabatanID',
        'roleID',
        'unitID',
        'satkerID',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
