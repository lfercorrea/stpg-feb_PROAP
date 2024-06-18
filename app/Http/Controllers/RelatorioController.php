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

        if($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()->format('d/m/Y H:i:s');
            $end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()->format('d/m/Y H:i:s');
            $query->whereHas('solicitacao', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('carimbo_data_hora', [$start_date, $end_date]);
            });
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

                if($request->filled('start_date') AND $request->filled('end_date')) {
                    $carimbo_data_hora = Carbon::createFromFormat('d/m/Y H:i:s', $solicitacao->carimbo_data_hora);
                    $start_date_obj = Carbon::createFromFormat('d/m/Y H:i:s', $start_date);
                    $end_date_obj = Carbon::createFromFormat('d/m/Y H:i:s', $end_date);
                    
                    if(!$carimbo_data_hora->between($start_date_obj, $end_date_obj)) {
                        continue;
                    }
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
            'tipo_solicitante' => $request->input('tipo_solicitante'),
            'programas' => $programas,
            'programas_selecionados' => $programas_selecionados ?? null,
            'start_date' => $start_date ?? null,
            'end_date' => $end_date ?? null,
        ]);
    }
}
