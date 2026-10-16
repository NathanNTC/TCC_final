<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Os atributos que devem ficar ocultos na serialização.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversões de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relacionamento 1:1 com Paciente.
     */
    public function paciente(): HasOne
    {
        return $this->hasOne(Paciente::class);
    }

    /**
     * Relacionamento 1:N com avaliações médicas.
     */
    public function avaliacoesMedicas(): HasMany
    {
        return $this->hasMany(
            AvaliacaoMedica::class,
            'avaliador_id'
        );
    }

    /**
     * Relacionamento 1:1 com Médico.
     */
    public function medico(): HasOne
    {
        return $this->hasOne(Medico::class);
    }

    /**
     * Envia a notificação personalizada de redefinição de senha.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(
            new ResetPasswordNotification($token)
        );
    }
}
