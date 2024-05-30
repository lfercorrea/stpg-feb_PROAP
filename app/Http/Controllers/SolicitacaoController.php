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

class SolicitacaoController extends Controller
{
    public function index(Request $request) {
        $count_solicitacoes = 0;

        if($request->has('search') OR $request->has('programa_id') OR $request->has('tipo_solicitacao_id') OR $request->has('status_id')){
            $solicitacoes = Solicitacao::search($request->search, $request->programa_id, $request->tipo_solicitacao_id, $request->status_id)
                ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
                ->paginate(30);
            $count_solicitacoes = Solicitacao::search($request->search, $request->programa_id, $request->tipo_solicitacao_id, $request->status_id)->count();
        }
        else{
            $solicitacoes = Solicitacao::with([
                'status',
                'tipo',
                'solicitante',
                'programa',
                'programaCategoria',
                'atividade',
                'evento',
                'material',
                'servico',
            ])
            ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
            ->paginate(30);
        }

        $tipos_solicitacao = SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        $programas = Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        $statuses = Status::all();
        $count_message = [];

        if(!empty($request->search)) {
            $count_message[] = "Termo buscado: <b><i>\"$request->search\"</i></b>";
        }
        
        if(!empty($request->tipo_solicitacao_id)) {
            $count_message[] = "Tipo: <b><i>{$tipos_solicitacao[$request->tipo_solicitacao_id]}</i></b>";
        }
        
        if(!empty($request->status_id)) {
            $status = $statuses->firstWhere('id', $request->status_id);
            $count_message[] = "Status: <b><i>{$status->nome}</i></b>";
        }

        if(!empty($request->programa_id)) {
            $arr_programas_selecionados = [];
            
            foreach($request->programa_id as $selected_id) {
                $arr_programas_selecionados[] = $programas[$selected_id];
            }

            $programas_selecionados = implode(', ', $arr_programas_selecionados);
            $count_message[] = "Programas: <b><i>{$programas_selecionados}</i></b>";
        }

        $plural = ($count_solicitacoes > 1) ? 's' : '';
        $search_message = implode("<br>", $count_message);
        
        return view('solicitacoes', [
            'page_title' => 'Estoque',
            'solicitacoes' => $solicitacoes,
            'count_solicitacoes' => $count_solicitacoes,
            'search_message' => $search_message,
            'tipos_solicitacao' => $tipos_solicitacao,
            'programas' => $programas,
            'statuses' => $statuses,
        ]);
    }

    public function show(string $id) {
        $solicitacao = Solicitacao::where('id', $id)
            ->with([
                'status',
                'tipo',
                'solicitante',
                'programa',
                'programaCategoria',
                'atividade',
                'evento',
                'material',
                'servico',
                'nota',
            ])->first();
            
        $resumo_solicitacao = optional($solicitacao->evento)->nome
            ?? optional($solicitacao->atividade)->descricao
            ?? optional($solicitacao->material)->descricao
            ?? optional($solicitacao->servico)->titulo_artigo;
        $link_artigo_aceite = optional($solicitacao->evento)->artigo_aceite;
        $link_artigo_copia = optional($solicitacao->evento)->artigo_copia;
        $link_parecer = optional($solicitacao->evento)->parecer_orientador 
            ?? optional($solicitacao->atividade)->parecer_orientador 
            ?? optional($solicitacao->material)->parecer_orientador 
            ?? optional($solicitacao->servico)->parecer_orientador;
        $link_orcamento = optional($solicitacao->evento)->orcamento_passagens 
            ?? optional($solicitacao->atividade)->orcamento_passagens 
            ?? optional($solicitacao->material)->orcamento 
            ?? optional($solicitacao->servico)->orcamento;

        return view('solicitacao', [
            'solicitacao' => $solicitacao,
            'resumo_solicitacao' => $resumo_solicitacao,
            'link_artigo_aceite' => $link_artigo_aceite,
            'link_artigo_copia' => $link_artigo_copia,
            'link_parecer' => $link_parecer,
            'link_orcamento' => $link_orcamento,
            'fontes_pagadoras' => FontePagadora::all(),
            'statuses' => Status::all(),
            'count_notas' => $solicitacao->nota->count(),
            'valor_total' => number_format($solicitacao->nota->sum('valor'), 2, ',', '.'),
        ]);
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'status_id' => 'required|numeric',
            'observacao' => 'nullable|string',
        ], [
            'status_id.required' => 'Deve ser fornecido um status para a solicitação',
            'status_id.numeric' => 'O status da solicitação deve ser do tipo INT',
            'observacao.string' => 'O campo observação deve ser do tipo STRING',
        ]);
        $solicitacao = Solicitacao::findOrFail($id);
        $solicitacao->status_id = $request->status_id;
        $solicitacao->observacao = $request->observacao;
        $solicitacao->save();

        return redirect()
            ->route('site.solicitacao', ['id' => $solicitacao->id])
            ->with('success', 'Status da solicitação alterado.');
    }
}
