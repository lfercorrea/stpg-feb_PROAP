<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTipo;
use App\Models\Status;
use App\Models\ValorTipo;
use App\Models\FontePagadora;
use Carbon\Carbon;

class SolicitacaoController extends Controller
{
    public function index(Request $request) {
        $count_solicitacoes = 0;
        $limit = 30;

        if($request->filled('limit') AND $request->input('limit') <= 1000) {
            $limit = $request->input('limit');
        }

        if($request->has('search') 
            OR ($request->has('start_date') AND $request->has('end_date')) 
            OR $request->has('programa_id') 
            OR $request->has('tipo_solicitacao_id') 
            OR $request->has('status_id')){
            $solicitacoes = Solicitacao::search(
                $request->search,
                $request->start_date,
                $request->end_date,
                $request->programa_id,
                $request->tipo_solicitacao_id,
                $request->status_id)
                    ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
                    ->paginate($limit);
            $count_solicitacoes = Solicitacao::search($request->search,
                $request->start_date,
                $request->end_date,
                $request->programa_id, 
                $request->tipo_solicitacao_id, 
                $request->status_id)
                    ->count();
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
                'traducao_artigo',
                'outro_servico',
                'manutencao',
            ])
            ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
            ->paginate($limit);
        }

        $tipos_solicitacao = SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        $programas = Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray();
        $statuses = Status::all();
        $count_message = [];

        if($request->filled('search')) {
            $count_message[] = "Termo buscado: <b><i>\"$request->search\"</i></b>";
        }
        
        if($request->filled('tipo_solicitacao_id')) {
            $count_message[] = "Tipo: <b><i>{$tipos_solicitacao[$request->tipo_solicitacao_id]}</i></b>";
        }
        
        if($request->filled('status_id')) {
            $status = $statuses->firstWhere('id', $request->status_id);
            $count_message[] = "Status: <b><i>{$status->nome}</i></b>";
        }

        if($request->filled('programa_id')) {
            $arr_programas_selecionados = [];
            
            foreach($request->programa_id as $selected_id) {
                $arr_programas_selecionados[] = $programas[$selected_id];
            }

            $programas_selecionados = implode(', ', $arr_programas_selecionados);
            $count_message[] = "Programas: <b><i>{$programas_selecionados}</i></b>";
        }
        
        if($request->filled('start_date') AND $request->filled('end_date')) {
            $start_date = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()->format('d/m/Y H:i:s');
            $end_date = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()->format('d/m/Y H:i:s');
            $count_message[] = "Período: de <b>{$start_date}</b> até <b>{$end_date}</b>";
        }

        $plural = ($count_solicitacoes > 1) ? 's' : '';
        $search_message = implode("<br>", $count_message);
        
        return view('solicitacoes', [
            'title' => 'Solicitações',
            'solicitacoes' => $solicitacoes->appends($request->except('page')),
            'count_solicitacoes' => $count_solicitacoes,
            'search_message' => $search_message,
            'tipos_solicitacao' => $tipos_solicitacao,
            'programas' => $programas,
            'statuses' => $statuses,
            'start_month' => Carbon::now()->startOfMonth()->toDateString(),
            'now' => Carbon::now()->toDateString(),
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
                'traducao_artigo',
                'outro_servico',
                'manutencao',
                'notas',
            ])->first();
            
        $resumo_solicitacao = optional($solicitacao->evento)->nome
            ?? optional($solicitacao->atividade)->descricao
            ?? optional($solicitacao->material)->descricao
            ?? optional($solicitacao->traducao_artigo)->titulo_artigo
            ?? optional($solicitacao->outro_servico)->descricao
            ?? optional($solicitacao->manutencao)->descricao;
        $justificativa = optional($solicitacao->evento)->justificativa
            ?? optional($solicitacao->atividade)->justificativa
            ?? optional($solicitacao->material)->justificativa
            ?? optional($solicitacao->traducao_artigo)->justificativa
            ?? optional($solicitacao->outro_servico)->justificativa
            ?? optional($solicitacao->manutencao)->justificativa;
        $link_artigo_aceite = optional($solicitacao->evento)->artigo_aceite;
        $link_artigo_copia = optional($solicitacao->evento)->artigo_copia
            ?? optional($solicitacao->traducao_artigo)->artigo_a_traduzir;
        $link_parecer = optional($solicitacao->evento)->parecer_orientador 
            ?? optional($solicitacao->atividade)->parecer_orientador 
            ?? optional($solicitacao->material)->parecer_orientador 
            ?? optional($solicitacao->traducao_artigo)->parecer_orientador;
        $link_orcamento = optional($solicitacao->evento)->orcamento_passagens 
            ?? optional($solicitacao->atividade)->orcamento_passagens 
            ?? optional($solicitacao->material)->orcamento 
            ?? optional($solicitacao->manutencao)->orcamento 
            ?? optional($solicitacao->outro_servico)->orcamento 
            ?? optional($solicitacao->traducao_artigo)->orcamento;

        return view('solicitacao', [
            'title' => 'Detalhes da solicitação',
            'solicitacao' => $solicitacao,
            'resumo_solicitacao' => $resumo_solicitacao,
            'justificativa' => $justificativa,
            'link_artigo_aceite' => $link_artigo_aceite,
            'link_artigo_copia' => $link_artigo_copia,
            'link_parecer' => $link_parecer,
            'link_orcamento' => $link_orcamento,
            'fontes_pagadoras' => FontePagadora::all(),
            'valor_tipos' => ValorTipo::all(),
            'statuses' => Status::all(),
            'count_notas' => $solicitacao->notas->count(),
            'valor_total' => number_format($solicitacao->notas->sum('valor'), 2, ',', '.'),
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
            ->route('site.solicitacao.show', ['id' => $solicitacao->id])
            ->with('success', 'Status da solicitação alterado.');
    }
}
