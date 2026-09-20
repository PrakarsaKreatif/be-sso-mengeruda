<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[Fillable(['name', 'email', 'password', 'nik', 'phone', 'ktp_path', 'is_approved', 'kk_path', 'is_kk_approved'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'nik' => $this->nik,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_approved' => (bool)$this->is_approved,
            'is_kk_approved' => (bool)$this->is_kk_approved,
            'kk_path' => $this->kk_path,
            'roles' => $this->roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                ];
            }),
            'applications' => $this->roles->flatMap->applications->pluck('name')->unique(),
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'is_kk_approved' => 'boolean',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function isWarga(): bool
    {
        return $this->roles()->where('name', 'warga')->exists();
    }

    public function isAdminSurat(): bool
    {
        return $this->roles()->whereIn('name', ['admin_surat', 'Super Admin', 'Admin'])->exists();
    }
}
