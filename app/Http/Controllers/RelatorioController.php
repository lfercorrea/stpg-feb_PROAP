<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Programa;
use App\Models\Solicitacao;
use App\Models\Status;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
            'end_date' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
        ], [
            'start_date.date_format' => 'A data inicial está no formato incorreto.',
            'start_date.after' => 'A data inicial deve ser posterior a 01/01/1900.',
            'start_date.before' => 'A data inicial deve ser anterior a 31/12/2099.',

            'end_date.date_format' => 'A data final está no formato incorreto.',
            'end_date.after' => 'A data final deve ser posterior a 01/01/1900.',
            'end_date.before' => 'A data final deve ser anterior a 31/12/2099.',
        ]);
        /**
         * inicialmente, o acesso na página gerava o relatório sem filtro.
         * considerei isso inconveniente do ponto de vista performático,
         * então resolvi aplicar o filtro por padrão
         */
        $obj_start_date = Carbon::now()->subMonth()->startOfMonth();
        $obj_end_date = Carbon::now()->subMonth()->endOfMonth();
        $str_start_date = $request->filled('start_date') ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()->format('d/m/Y H:i:s') : $obj_start_date->format('d/m/Y H:i:s');
        $str_end_date = $request->filled('end_date') ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()->format('d/m/Y H:i:s') : $obj_end_date->format('d/m/Y H:i:s');
        $sql = "datetime(
                substr(carimbo_data_hora, 7, 4) || '-' || 
                substr(carimbo_data_hora, 4, 2) || '-' || 
                substr(carimbo_data_hora, 1, 2) || ' ' || 
                substr(carimbo_data_hora, 12, 8)
            ) BETWEEN datetime(
                substr(?, 7, 4) || '-' || 
                substr(?, 4, 2) || '-' || 
                substr(?, 1, 2) || ' ' || 
                substr(?, 12, 8)
            ) AND datetime(
                substr(?, 7, 4) || '-' || 
                substr(?, 4, 2) || '-' || 
                substr(?, 1, 2) || ' ' || 
                substr(?, 12, 8)
            )";

        $tipo_solicitante = $request->input('tipo_solicitante');
        $arr_programa_id = $request->input('programa_id');

        $query = Solicitacao::whereRaw($sql, [$str_start_date, $str_start_date, $str_start_date, $str_start_date, $str_end_date, $str_end_date, $str_end_date, $str_end_date])
            ->join('solicitantes', function ($join) use ($request, $tipo_solicitante) {
                $join->on('solicitantes.id', '=', 'solicitacoes.solicitante_id')
                    ->when($request->filled('tipo_solicitante'), function($q) use ($tipo_solicitante) {
                        $q->where('tipo_solicitante', $tipo_solicitante);
                    });
            })->orderBy('programas.nome', 'asc')->orderBy('solicitantes.nome')
            ->join('programas', function($join) use ($arr_programa_id, $request) {
                $join->on('programas.id', '=', 'solicitacoes.programa_id')
                    ->when($request->filled('programa_id'), function($q) use ($arr_programa_id) {
                        $q->whereIn('programa_id', $arr_programa_id);
                    });
            })
            ->join('notas', function($join) {
                $join->on('notas.solicitacao_id', '=', 'solicitacoes.id')
                    ->where('notas.valor', '>', 0);
            })
            ->join('solicitacao_tipos', 'solicitacoes.tipo_solicitacao_id', '=', 'solicitacao_tipos.id')
            ->leftJoin('servico_tipos', 'solicitacoes.servico_tipo_id', '=', 'servico_tipos.id')
            ->select(
                'programas.id as programa_id',
                'programas.nome as programa_nome',
                'programas.saldo_inicial as saldo_inicial',
                'solicitantes.id as solicitante_id',
                'solicitantes.nome as solicitante_nome',
                'solicitantes.tipo_solicitante as tipo_solicitante',
                'solicitacoes.id as solicitacao_id',
                'solicitacao_tipos.nome as solicitacao_tipo',
                'servico_tipos.nome as solicitacao_servico_tipo',
                DB::raw('SUM(notas.valor) as solicitacao_soma_notas'),
            )->groupBy(
                'programas.nome',
                'programas.saldo_inicial',
                'solicitantes.nome',
                'solicitantes.id',
                'solicitacoes.id',
            )->orderByRaw("datetime(
                substr(carimbo_data_hora, 7, 4) || '-' || 
                substr(carimbo_data_hora, 4, 2) || '-' || 
                substr(carimbo_data_hora, 1, 2) || ' ' || 
                substr(carimbo_data_hora, 12, 8)
            ) DESC"
        )->get();

        $programas = $query->groupBy('programa_id')->map(function($programa) {
            return (object) [
                'id' => $programa->first()->programa_id,
                'nome' => $programa->first()->programa_nome,
                'saldo_inicial' => $programa->first()->saldo_inicial,
                'count' => $programa->count(),
                'solicitantes' => $programa->groupBy('solicitante_id')->map(function($solicitante) {
                    return (object) [
                        'id' => $solicitante->first()->solicitante_id,
                        'nome' => $solicitante->first()->solicitante_nome,
                        'tipo' => $solicitante->first()->tipo_solicitante,
                        'solicitacoes' => $solicitante->map(function($solicitacao) {
                            return (object) [
                                'id' => $solicitacao->solicitacao_id,
                                'tipo' => $solicitacao->solicitacao_tipo,
                                'servico_tipo' => $solicitacao->solicitacao_servico_tipo,
                                'soma_notas' => $solicitacao->solicitacao_soma_notas,
                            ];
                        })
                    ];
                })->values(),
            ];
        })->values();
            
        $lista_programas = Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        $title = 'Relatório consolidado';
    
        if($request->filled('programa_id')) {
            foreach($request->input('programa_id') as $key => $programa_id) {
                $arr_nome_programas[] = $lista_programas[$programa_id];
            }
    
            $title .= ' - ' . $programas_selecionados = implode(', ', $arr_nome_programas);
        }
    
        return view('relatorio_programa', [
            'title' => $title,
            'gastos_programa' => 0,
            'gastos_solicitante' => 0,
            'total_geral' => 0,
            'lista_programas' => $lista_programas,
            'statuses' => Status::all(),
            'tipo_solicitante' => $request->input('tipo_solicitante') ?? false,
            'programas' => $programas,
            'programas_selecionados' => $programas_selecionados ?? false,
            // 'start_month' => Carbon::now()->startOfMonth()->toDateString(),
            // 'now' => Carbon::now()->toDateString(),
            'start_time' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
            'end_time' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
            'start_date' => $str_start_date ?? false,
            'end_date' => $str_end_date ?? false,
        ]);
    }    
}
