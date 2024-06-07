<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitacao;
use App\Models\Solicitante;

class SolicitanteController extends Controller
{
    /**
     * lista todos os solicitantes
     */
    public function index(Request $request) {
        $count_solicitantes = 0;
        $limite = empty($request->limite_paginacao) ? 30 : $request->limite_paginacao;

        if($request->has('search') OR $request->has('tipo_solicitante')){
            $solicitantes = Solicitante::search($request->search, $request->tipo_solicitante)
                ->paginate($limite);
            $count_solicitantes = Solicitante::search($request->search, $request->tipo_solicitante)->count();
        }
        else{
            $solicitantes = Solicitante::orderBy('nome', 'asc')
                ->paginate($limite);
        }

        $solicitante_tipos = Solicitante::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        $count_message = [];

        if(!empty($request->search)) {
            $count_message[] = "Termo buscado: <b><i>\"$request->search\"</i></b>";
        }
        
        if(!empty($request->tipo_solicitante)) {
            $count_message[] = "Tipo de solicitante: <b><i>$request->tipo_solicitante</i></b>";
        }

        $plural = ($count_solicitantes > 1) ? 's' : '';
        $search_message = implode("<br>", $count_message);
        
        return view('solicitantes', [
            'title' => 'Solicitantes',
            'solicitantes' => $solicitantes->appends($request->except('page')),
            'count_solicitantes' => $count_solicitantes,
            'search_message' => $search_message,
        ]);
    }
    /**
     * mostra a página de visualização com dados do solicitante
     */
    public function show(string $id) {
        return view('solicitante', [
            'title' => 'Solicitante',
            'solicitante' => Solicitante::where('id', $id)->first(),
            'solicitacoes' => Solicitacao::with([
                'nota.valor_tipo',
                'tipo',
                'solicitante',
                'programa',
                'programaCategoria',
                'atividade',
                'evento',
                'material',
                'traducao_artigo',
                'outro_servico',
                'manutencao',
            ])
                ->where('solicitante_id', $id)
                ->orderByRaw(" STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC ")
                ->get()
                ->groupBy('programa_id'),
            'valor_total' => 0,
            'valor_total_programa' => 0,
        ]);
    }
}
