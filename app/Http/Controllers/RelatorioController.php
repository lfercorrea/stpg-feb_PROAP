<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Solicitante;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTipo;
use App\Models\Status;

class RelatorioController extends Controller
{
    public function index(Request $request) {
        $query = Solicitante::with(['solicitacao.programa']);
        
        if($request->filled('tipo_solicitante')) {
            $query->where('tipo_solicitante', $request->tipo_solicitante);
        }
        
        if($request->filled('programa_id')) {
            $arr_programa_id = $request->input('programa_id');
            $query->whereHas('solicitacao', function($q) use ($arr_programa_id) {
                $q->whereIn('programa_id', $arr_programa_id);
            });
        }
    
        $solicitantes = $query->get();
        $solicitantes_por_programa = collect();
    
        foreach($solicitantes as $solicitante) {
            // agrupar os valores gastos por programa
            $valores_por_programa = [];
            foreach($solicitante->solicitacao as $solicitacao) {
                $programa_nome = $solicitacao->programa->nome;
                // correção do bug onde mesmo nao marcado, o programa
                // aparecia se o solicitante tivesse solicitacao em outro programa
                $programa_id = $solicitacao->programa->id;
    
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
                    'valor_gasto' => $valor_total
                ]);
            }
            // ufa. consegui desfoder essa merda
        }
    
        return view('relatorio_programa', [
            'total_programa' => 0,
            'total_geral' => 0,
            'solicitantes_por_programa' => $solicitantes_por_programa,
            'tipos_solicitacao' => SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'programas' => Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'statuses' => Status::all(),
        ]);
    }
}
