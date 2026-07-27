<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\roles;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userID';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'userID',
        'username',
        'password',
        'name',
        'nip',
        'email',
        'status',
        'email_verified_at',
        'last_login_at',
        'jabatanID',
        'is_data_confirmed',
        'data_confirmed_at',
        'roleID',
        'unitID',
        'satkerID',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'data_confirmed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_data_confirmed' => 'boolean',
    ];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatanID', 'jabatanID');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(roles::class, 'roleID', 'roleID');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unitID', 'unitID');
    }

    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class, 'satkerID', 'satkerID');
    }
}