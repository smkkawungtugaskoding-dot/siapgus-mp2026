<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'username',
        'password',
        'full_name',
        'role',
        'nik_nuptk',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru' || $this->role === 'teacher';
    }

    public function isTeacher(): bool
    {
        return $this->isGuru();
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }
        return $this->role === $roles;
    }

    public function getDashboardRoute(): string
    {
        return $this->isAdmin() ? route('admin.dashboard') : route('guru.dashboard');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }

    public function homeroomAssignments()
    {
        return $this->hasManyThrough(HomeroomAssignment::class, Teacher::class, 'user_id', 'teacher_id', 'id', 'id');
    }

    public function waliKelas()
    {
        return $this->hasMany(WaliKelas::class, 'guru_id', 'id');
    }
}
