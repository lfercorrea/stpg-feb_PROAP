<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\SolicitacoesDiscentesImport;
use App\Imports\SolicitacoesDocentesImport;
use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Material;
use App\Models\Servico;
use App\Models\ImportacoesDiscentes;
use App\Models\Programa;
use App\Models\ProgramaCategoria;
use App\Models\Solicitante;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTipo;
use App\Models\Status;
use App\Models\FontePagadora;

class RelatorioController extends Controller
{
    public function index(Request $request) {
        // $solicitante = Solicitante::find($id);

        // if(!$solicitante) {
        //     return redirect()->route('solicitantes.index')->with('fail', 'Solicitante não encontrado.');
        // }
        // $solicitacoes = Solicitacao::with([
        //             'status',
        //             'tipo',
        //             'solicitante',
        //             'programa',
        //             'programaCategoria',
        //             'atividade',
        //             'evento',
        //             'material',
        //             'traducao_artigo',
        //             'outro_servico',
        //             'manutencao',
        //         ])
        //         ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
        //         ->get();

        // $solicitantes = Solicitante::all();
        // $solicitantesPorPrograma = Solicitante::with([
        //         'solicitacao.programa' // Carregar o programa relacionado às solicitações de cada solicitante
        //     ])
        //     ->has('solicitacao') // Garantir que haja pelo menos uma solicitação relacionada
        //     ->get()
        //     ->groupBy(function ($solicitante) {
        //         return $solicitante->solicitacao->first()->programa->nome;
        // });
        // $solicitantes = Solicitante::with([
        //         'solicitacao.programa',
        //     ])
        //     ->has('solicitacao')
        //     ->get()
        //     ->groupBy(function($solicitante) {
        //         return $solicitante->solicitacao->first()->programa->nome;
        // });
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

        return view('formulario_relatorio', [
            'total_programa' => 0,
            'solicitantes_por_programa' => $solicitantes_por_programa,
            'tipos_solicitacao' => SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'programas' => Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'statuses' => Status::all(),
        ]);
        // $count_solicitacoes = 0;

        // if($request->has('search') OR $request->has('programa_id') OR $request->has('tipo_solicitacao_id') OR $request->has('status_id')){
        //     $solicitacoes = Solicitacao::search($request->search, $request->programa_id, $request->tipo_solicitacao_id, $request->status_id)
        //         ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
        //         ->get();
        //     $count_solicitacoes = Solicitacao::search($request->search, $request->programa_id, $request->tipo_solicitacao_id, $request->status_id)->count();
        // }
        // else{
        //     $solicitacoes = Solicitacao::with([
        //         'status',
        //         'tipo',
        //         'solicitante',
        //         'programa',
        //         'programaCategoria',
        //         'atividade',
        //         'evento',
        //         'material',
        //         'traducao_artigo',
        //         'outro_servico',
        //         'manutencao',
        //     ])
        //     ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
        //     ->paginate(30);
        // }

        // $tipos_solicitacao = SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        // $programas = Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        // $statuses = Status::all();
        // $count_message = [];

        // if(!empty($request->search)) {
        //     $count_message[] = "Termo buscado: <b><i>\"$request->search\"</i></b>";
        // }
        
        // if(!empty($request->tipo_solicitacao_id)) {
        //     $count_message[] = "Tipo: <b><i>{$tipos_solicitacao[$request->tipo_solicitacao_id]}</i></b>";
        // }
        
        // if(!empty($request->status_id)) {
        //     $status = $statuses->firstWhere('id', $request->status_id);
        //     $count_message[] = "Status: <b><i>{$status->nome}</i></b>";
        // }

        // if(!empty($request->programa_id)) {
        //     $arr_programas_selecionados = [];
            
        //     foreach($request->programa_id as $selected_id) {
        //         $arr_programas_selecionados[] = $programas[$selected_id];
        //     }

        //     $programas_selecionados = implode(', ', $arr_programas_selecionados);
        //     $count_message[] = "Programas: <b><i>{$programas_selecionados}</i></b>";
        // }

        // $plural = ($count_solicitacoes > 1) ? 's' : '';
        // $search_message = implode("<br>", $count_message);
        
        // return view('formulario_relatorio', [
        //     'solicitacoes' => $solicitacoes,
        //     'count_solicitacoes' => $count_solicitacoes,
        //     'search_message' => $search_message,
        //     'tipos_solicitacao' => $tipos_solicitacao,
        //     'programas' => $programas,
        //     'statuses' => $statuses,
        // ]);
    }
}
