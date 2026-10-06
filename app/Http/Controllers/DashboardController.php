<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $usuario = Auth::user();

        $paciente = $usuario->paciente;

        if (!$paciente) {
            return Inertia::render('Dashboard', [
                'perfilCompletude' => 0,

                'perfilSecoes' => [
                    'dados_pessoais' => false,
                    'acessibilidade' => false,
                    'condicoes_saude' => false,
                    'historico_familiar' => false,
                    'alergias' => false,
                    'medicamentos' => false,
                    'cirurgias' => false,
                    'habitos_medidas' => false,
                ],
            ]);
        }

        $perfilSaude = $paciente->perfilSaude;

        /*
        |--------------------------------------------------------------------------
        | 1. Dados pessoais
        |--------------------------------------------------------------------------
        |
        | Todos os dados básicos precisam estar preenchidos.
        |
        */

        $dadosPessoais = (
            filled($paciente->cpf) &&
            filled($paciente->sexo) &&
            filled($paciente->data_nascimento) &&
            filled($paciente->telefone) &&
            filled($paciente->cidade) &&
            filled($paciente->estado) &&
            filled($paciente->profissao)
        );


        /*
        |--------------------------------------------------------------------------
        | 2. Necessidades de acessibilidade
        |--------------------------------------------------------------------------
        |
        | A seção é considerada preenchida quando existe uma resposta.
        |
        | Se não houver nenhuma deficiência, isso também representa
        | uma resposta válida.
        |
        */

        $acessibilidade = $perfilSaude !== null;


        /*
        |--------------------------------------------------------------------------
        | 3. Condições de saúde
        |--------------------------------------------------------------------------
        |
        | O próprio registro do perfil representa que a seção foi
        | preenchida. Uma lista vazia significa que o paciente
        | não possui condições informadas.
        |
        */

        $condicoesSaude = $perfilSaude !== null;


        /*
        |--------------------------------------------------------------------------
        | 4. Histórico familiar
        |--------------------------------------------------------------------------
        */

        $historicoFamiliar = $perfilSaude !== null;


        /*
        |--------------------------------------------------------------------------
        | 5. Alergias
        |--------------------------------------------------------------------------
        */

        $alergias = $perfilSaude !== null;


        /*
        |--------------------------------------------------------------------------
        | 6. Medicamentos e suplementos
        |--------------------------------------------------------------------------
        |
        | Se o paciente não utiliza medicamentos, a ausência de registros
        | também é uma resposta válida.
        |
        */

        $medicamentos = $paciente->medicamentos !== null;


        /*
        |--------------------------------------------------------------------------
        | 7. Cirurgias anteriores
        |--------------------------------------------------------------------------
        |
        | Nenhuma cirurgia também é uma resposta válida.
        |
        */

        $cirurgias = $paciente->cirurgias !== null;


        /*
        |--------------------------------------------------------------------------
        | 8. Hábitos e medidas
        |--------------------------------------------------------------------------
        |
        | Os hábitos são obrigatórios.
        | Altura e peso NÃO impedem o perfil de chegar a 100%.
        |
        */

        $habitosMedidas = (
            $perfilSaude !== null &&
            $perfilSaude->fumante !== null &&
            $perfilSaude->alcoolismo !== null &&
            $perfilSaude->uso_drogas !== null &&
            $perfilSaude->atividade_fisica_regular !== null
        );


        /*
        |--------------------------------------------------------------------------
        | Situação das 8 seções
        |--------------------------------------------------------------------------
        */

        $secoes = [
            'dados_pessoais' => $dadosPessoais,
            'acessibilidade' => $acessibilidade,
            'condicoes_saude' => $condicoesSaude,
            'historico_familiar' => $historicoFamiliar,
            'alergias' => $alergias,
            'medicamentos' => $medicamentos,
            'cirurgias' => $cirurgias,
            'habitos_medidas' => $habitosMedidas,
        ];


        /*
        |--------------------------------------------------------------------------
        | Cálculo da porcentagem
        |--------------------------------------------------------------------------
        |
        | Existem 8 seções.
        |
        | Cada seção vale:
        |
        | 100 / 8 = 12,5%
        |
        */

        $totalSecoes = count($secoes);

        $secoesPreenchidas = count(
            array_filter($secoes)
        );

        $percentual = $totalSecoes > 0
            ? round(($secoesPreenchidas / $totalSecoes) * 100)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Dashboard', [
            'perfilCompletude' => $percentual,

            'perfilSecoes' => $secoes,
        ]);
    }
}

