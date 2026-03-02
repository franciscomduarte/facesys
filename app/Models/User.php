<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CausesActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'empresa_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'created_by');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMedico(): bool
    {
        return $this->role === 'medico';
    }

    public function isRecepcionista(): bool
    {
        return $this->role === 'recepcionista';
    }

    public function documentosAssinaveisProfissional()
    {
        return $this->hasMany(DocumentoAssinavel::class, 'profissional_id');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'profissional_id');
    }
}
