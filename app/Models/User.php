<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\\Database\\Factories\\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'nama_lengkap',
        'nik',
        'alamat_lengkap',
        'no_telephone',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function bookings() { return $this->hasMany(Booking::class, 'user_id'); }
    public function pemesanans() { return $this->hasMany(Pemesanan::class, 'user_id'); }
    public function notifikasis() { return $this->hasMany(Notifikasi::class, 'user_id'); }

    public function setRoleAttribute($value) { $this->attributes['role'] = $value; }

    protected function username(): Attribute
    {
        return Attribute::make(
            set: fn ($v) => is_string($v) ? strtolower($v) : $v
        );
    }

    public function setting()
    {
        return $this->hasOne(\App\Models\UserSetting::class, 'user_id');
    }

    public function syncSpatieRoleFromEnum(): void
    {
        if ($this->role && in_array($this->role, ['admin','user','bendahara','takmir'])) {
            $this->syncRoles([$this->role]);
        }
    }

    public function getRouteKeyName(): string
    {
        return 'user_id';
    }
}
