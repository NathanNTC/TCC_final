<?php

namespace App\Services\Avaliacao;

use App\Models\Convite;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ConviteService
{
    /**
     * Perfis que podem ser convidados para avaliar triagens.
     */
    public const PERFIS_PERMITIDOS = [
        'Estudante',
        'Residente',
        'Medico',
    ];

    public function gerar(
        User $administrador,
        string $perfil,
        ?string $expiraEm = null
    ): Convite {
        if (! in_array($perfil, self::PERFIS_PERMITIDOS, true)) {
            throw ValidationException::withMessages([
                'perfil' => 'O perfil selecionado não pode avaliar triagens.',
            ]);
        }

        return Convite::create([
            'codigo' => Str::upper(Str::random(8)),
            'perfil' => $perfil,
            'criado_por' => $administrador->id,
            'expira_em' => $expiraEm,
            'ativo' => true,
        ]);
    }

    /**
     * Valida o convite e registra seu uso dentro da mesma transação.
     */
    public function utilizar(string $codigo, User $usuario): Convite
    {
        return DB::transaction(function () use ($codigo, $usuario) {
            $convite = Convite::where('codigo', Str::upper(trim($codigo)))
                ->lockForUpdate()
                ->first();

            if (! $convite || ! $convite->estaValido()) {
                throw ValidationException::withMessages([
                    'codigo' => 'Convite inválido, expirado ou já utilizado.',
                ]);
            }

            $convite->update([
                'utilizado_por' => $usuario->id,
                'utilizado_em' => now(),
                'ativo' => false,
            ]);

            return $convite->fresh();
        });
    }
}