<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Solicitante;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTipo;
use App\Models\Status;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function index(Request $request) {
        /**
         * inicialmente, o acesso na página gerava o relatório sem filtro.
         * considerei isso inconveniente do ponto de vista performático,
         * então resolvi aplicar o filtro por padrão
         */
        $obj_start_date = Carbon::now()->subMonth()->startOfMonth();
        $obj_end_date = Carbon::now()->subMonth()->endOfMonth();
        $str_start_date = $request->filled('start_date') ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()->format('d/m/Y H:i:s') : $obj_start_date->format('d/m/Y H:i:s');
        $str_end_date = $request->filled('end_date') ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()->format('d/m/Y H:i:s') : $obj_end_date->format('d/m/Y H:i:s');
        $sql = "STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') BETWEEN STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s') AND STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s')";
    
        $query = Programa::with([
            'solicitacoes.solicitante',
            'solicitantes',
            ])
                ->orderBy('nome', 'asc');

        $query->whereHas('solicitacoes', function($q) use ($sql, $str_start_date, $str_end_date) {
            $q->whereRaw($sql, [$str_start_date, $str_end_date])
                ->whereHas('notas', function($subQ) {
                    $subQ->havingRaw('SUM(valor) > 0')
                        ->groupBy('id');
                });
        });
        
        if($request->filled('tipo_solicitante')) {
            $tipo_solicitante = $request->input('tipo_solicitante');
            $query->whereHas('solicitacoes.solicitante', function($q) use ($tipo_solicitante) {
                $q->where('tipo_solicitante', $tipo_solicitante);
            });
        }
        
        if($request->filled('programa_id')) {
            $arr_programa_id = $request->input('programa_id');
            $query->whereIn('id', $arr_programa_id);
        }
    
        /**
         * este encadeamento em $query->whereHas() serve tão somente para melhorar a performance e economizar
         * memória. o código funciona perfeitamente acessando diretamente com $query->with(). no entanto, sem
         * whereHas(), TODOS os solicitantes serão consultados e armazenados em memória, além de serem todos
         * iterados nos loops a seguir, reduzindo drasticamente a performance
         */
        $query->with('solicitacoes', function($q) use ($sql, $str_start_date, $str_end_date) {
            $q->whereRaw($sql, [$str_start_date, $str_end_date]);
        });

        $query->whereHas('solicitacoes', function($q) use ($sql, $str_start_date, $str_end_date) {
            $q->whereRaw($sql, [$str_start_date, $str_end_date]);
        });

        $programas = $query->get();

        foreach($programas as $programa) {
            $programa->solicitacoes = $programa->solicitacoes->filter(function($solicitacao) use ($programa) {
                $soma_notas = $solicitacao->soma_notas();

                if($soma_notas > 0) {
                    if($solicitacao->programa_id === $programa->id) {
                        $solicitacao->soma_notas = $soma_notas;
                        $solicitacao->id_solicitante = $solicitacao->solicitante->id;
                        $solicitacao->nome_solicitante = $solicitacao->solicitante->nome;
                        $solicitacao->tipo_solicitante = $solicitacao->solicitante->tipo_solicitante;
                        
                        return $solicitacao;
                    }
                }
            })->values();
        }
    
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
