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
        $query = Solicitante::with(['solicitacao.programa'])
            ->orderBy('nome', 'asc');
        
        if($request->filled('tipo_solicitante')) {
            $query->where('tipo_solicitante', $request->tipo_solicitante);
        }
        
        if($request->filled('programa_id')) {
            $arr_programa_id = $request->input('programa_id');
            $query->whereHas('solicitacao', function($q) use ($arr_programa_id) {
                $q->whereIn('programa_id', $arr_programa_id);
            });
        }

        if($request->filled('start_date') AND $request->filled('end_date')) {
            $obj_start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
            $obj_end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            $str_start_date = $obj_start_date->format('d/m/Y H:i:s');
            $str_end_date = $obj_end_date->format('d/m/Y H:i:s');
            $sql = "STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') BETWEEN STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s') AND STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s')";
            
            /**
             * este encadeamento em $query->whereHas() serve tão somente para melhorar a performance e economizar
             * memória. o código funciona perfeitamente acessando diretamente com $query->with(). no entanto, sem
             * whereHas(), TODOS os solicitantes serão consultados e armazenados em memória, além de serem todos
             * iterados nos loops a seguir, reduzindo drasticamente a performance
             */
            $query->whereHas('solicitacao', function($q) use ($str_start_date, $str_end_date, $sql) {
                $q->whereRaw($sql, [$str_start_date, $str_end_date]);
            });
            
            $query->with(['solicitacao' => function($q) use ($str_start_date, $str_end_date, $sql) {
                $q->whereRaw($sql, [$str_start_date, $str_end_date]);
            }]);
        }
    
        $solicitantes = $query->get();
        $solicitantes_por_programa = collect();
    
        foreach($solicitantes as $solicitante) {
            // agrupar os valores gastos por programa
            $valores_por_programa = [];
            foreach($solicitante->solicitacao as $solicitacao) {
                $programa = $solicitacao->programa;
                $programa_nome = $programa->nome;
                // correção do bug onde mesmo nao marcado, o programa
                // aparecia se o solicitante tivesse solicitacao em outro programa
                $programa_id = $programa->id;
    
                if($request->filled('programa_id') AND !in_array($programa_id, $request->input('programa_id'))) {
                    continue;
                }
    
                $valor_gasto = $solicitacao->soma_notas();
    
                if($valor_gasto > 0) {
                    if(!isset($valores_por_programa[$programa_nome])) {
                        $valores_por_programa[$programa_nome] = 0;
                    }
                    $valores_por_programa[$programa_nome] += $valor_gasto;
                }
            }
    
            // botar o solicitante aos programas correspondentes
            foreach($valores_por_programa as $programa_nome => $valor_total) {
                if(!$solicitantes_por_programa->has($programa_nome)) {
                    $solicitantes_por_programa[$programa_nome] = collect();
                }
                // botar o solicitante só se ele ainda não foi adicionado
                $solicitantes_por_programa[$programa_nome]->push([
                    'solicitante' => $solicitante,
                    'valor_gasto' => $valor_total,
                    'saldo_inicial' => $programa->saldo_inicial,
                    'saldo_restante' => $programa->saldo_inicial - $valor_total
                ]);
            }
            // ufa. consegui desfoder essa merda
        }

        $programas = Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();

        $title = 'Relatório consolidado';

        if($request->filled('programa_id')) {
            foreach($request->input('programa_id') as $key => $programa_id) {
                $arr_nome_programas[] = $programas[$programa_id];
            }

            $title .= ' - ' . $programas_selecionados = implode(', ', $arr_nome_programas);
        }
    
        return view('relatorio_programa', [
            'title' => $title,
            'total_programa' => 0,
            'total_geral' => 0,
            'solicitantes_por_programa' => $solicitantes_por_programa,
            'statuses' => Status::all(),
            'tipo_solicitante' => $request->input('tipo_solicitante') ?? false,
            'programas' => $programas,
            'programas_selecionados' => $programas_selecionados ?? false,
            'start_month' => Carbon::now()->startOfMonth()->toDateString(),
            'now' => Carbon::now()->toDateString(),
            'start_date' => $str_start_date ?? false,
            'end_date' => $str_end_date ?? false,
        ]);
    }
}
