<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    public const ROLE_LABELS = [
        'super_admin' => 'Super Admin',
        'admin_klinik' => 'Admin Klinik',
        'pendaftaran' => 'Pendaftaran',
        'perawat' => 'Perawat',
        'dokter' => 'Dokter',
        'apoteker' => 'Apoteker',
        'kasir' => 'Kasir',
        'manajemen' => 'Manajemen',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'activated_at',
        'password_changed_at',
        'last_login_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'activated_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function roleLabel(): string
    {
        return $this->roles
            ->map(fn ($role) => self::ROLE_LABELS[$role->name] ?? ucwords(str_replace('_', ' ', $role->name)))
            ->join(', ') ?: '—';
    }
}
