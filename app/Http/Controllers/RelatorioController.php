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
        $solicitantes_por_programa = $solicitantes->filter(function($solicitante) {
            return $solicitante->soma_notas() > 0;
        })->groupBy(function($solicitante) {
            return $solicitante->solicitacao->first()->programa->nome;
        });

        return view('relatorio_form', [
            'total_programa' => 0,
            'total_geral' => 0,
            'solicitantes_por_programa' => $solicitantes_por_programa,
            'tipos_solicitacao' => SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'programas' => Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'statuses' => Status::all(),
        ]);
    }
}
