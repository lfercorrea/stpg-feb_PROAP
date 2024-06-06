<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ImportacoesDiscentes;
use App\Models\ImportacoesDocentes;
use App\Models\Solicitacao;
use App\Models\Solicitante;

class SiteController extends Controller
{
    public function index() {
        return view('index', [
            //
        ]);
    }

    public function importacoes() {
        $solicitacoes_discentes = ImportacoesDiscentes::orderBy('id', 'asc')->get();
        $solicitacoes_docentes = ImportacoesDocentes::orderBy('id', 'asc')->get();
        $discentes_vazia = ImportacoesDiscentes::count() === 0;
        $docentes_vazia = ImportacoesDocentes::count() === 0;

        return view('importacoes', [
            'solicitacoes_discentes' => $solicitacoes_discentes,
            'solicitacoes_docentes' => $solicitacoes_docentes,
            'discentes_vazia' => $discentes_vazia,
            'docentes_vazia' => $docentes_vazia,
        ]);
    }

    /**
     * mostra a página de visualização com dados do solicitante
     */
    public function solicitante(string $id) {
        return view('solicitante', [
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

    public function solicitantes(Request $request) {
        $count_solicitantes = 0;

        if($request->has('search') OR $request->has('tipo_solicitante')){
            $solicitantes = Solicitante::search($request->search, $request->tipo_solicitante)
                ->paginate($request->limite_paginacao);
            $count_solicitantes = Solicitante::search($request->search, $request->tipo_solicitante)->count();
        }
        else{
            $solicitantes = Solicitante::orderBy('nome', 'asc')
                ->paginate($request->limite_paginacao);
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
            'solicitantes' => $solicitantes->appends($request->except('page')),
            'count_solicitantes' => $count_solicitantes,
            'search_message' => $search_message,
        ]);
    }
}
