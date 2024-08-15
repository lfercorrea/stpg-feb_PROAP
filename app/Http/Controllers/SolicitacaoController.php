<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ValoresHelper;
use App\Models\Nota;
use App\Models\Programa;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTipo;
use App\Models\Status;
use App\Models\ValorTipo;
use App\Models\FontePagadora;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class SolicitacaoController extends Controller
{
    public function index(Request $request) {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
            'end_date' => 'nullable|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
        ], [
            'start_date.date_format' => 'A data inicial está no formato incorreto.',
            'start_date.after' => 'A data inicial deve ser posterior a 01/01/1900.',
            'start_date.before' => 'A data inicial deve ser anterior a 31/12/2099.',

            'end_date.date_format' => 'A data final está no formato incorreto.',
            'end_date.after' => 'A data final deve ser posterior a 01/01/1900.',
            'end_date.before' => 'A data final deve ser anterior a 31/12/2099.',
        ]);

        $count_solicitacoes = 0;
        $limit = 30;
        
        if($request->filled('limit') AND $request->input('limit') <= 1000) {
            $limit =  $request->input('limit');
        }

        $query = Solicitacao::search(
            $request->input('search'),
            $request->input('start_date'),
            $request->input('end_date'),
            $request->input('programa_id'),
            $request->input('tipo_solicitacao_id'),
            $request->input('status_id')
        );
        
        $count_solicitacoes = $query->count();
        $solicitacoes = $query->paginate($limit);
        
        foreach($solicitacoes as $solicitacao) {
            $resumo_solicitacao = optional($solicitacao->evento)->nome
                ?? optional($solicitacao->atividade)->descricao
                ?? optional($solicitacao->material)->descricao
                ?? optional($solicitacao->traducao_artigo)->titulo_artigo
                ?? optional($solicitacao->outro_servico)->descricao
                ?? optional($solicitacao->manutencao)->descricao;
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

            $solicitacao->resumo = $resumo_solicitacao;
            $solicitacao->artigo_aceite = $link_artigo_aceite;
            $solicitacao->artigo_copia = $link_artigo_copia;
            $solicitacao->parecer_orientador = $link_parecer;
            $solicitacao->orcamento = $link_orcamento;
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
            'total_pago' => 0,
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
        $valor_inscricao = optional($solicitacao->evento)->valor_inscricao;
        $valor_passagens = optional($solicitacao->evento)->valor_passagens
            ?? optional($solicitacao->atividade)->valor_passagens;
        $valor_diarias = optional($solicitacao->evento)->valor_diarias
            ?? optional($solicitacao->atividade)->valor_diarias;
        $valor = optional($solicitacao->manutencao)->valor
            ?? optional($solicitacao->material)->valor
            ?? optional($solicitacao->outro_servico)->valor
            ?? optional($solicitacao->traducao_artigo)->valor;

        return view('solicitacao', [
            'title' => 'Detalhes da solicitação' . ' - ' . $solicitacao->solicitante->nome,
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
            'valor' => $valor,
            'valor_inscricao' => $valor_inscricao,
            'valor_passagens' => $valor_passagens,
            'valor_diarias' => $valor_diarias,
            'valor_total' => number_format($solicitacao->notas->sum('valor'), 2, ',', '.'),
        ]);
    }

    public function recibo(string $id, $nid) {
        App::setLocale('pt_BR');
        Carbon::setLocale('pt_BR');

        $nota = Nota::where('id', $nid)->first();

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

        switch ($solicitacao->solicitante->tipo_solicitante) {
            case 'Discente':
                $recibo = 'recibo_a';
                break;
            case 'Docente Permanente':
                $recibo = 'recibo_b';
                break;
            case 'Docente Colaborador':
                $recibo = 'recibo_b';
                break;
        }
        $valor_total = number_format($nota->valor, 2, ',', '.');
        $tipo_valor = ValorTipo::where('id', $nota->valor_tipo_id)->first();

        $data_nota = Carbon::createFromFormat('Y-m-d', $nota->data);
        $data_impressao = $data_nota->format('d/m/Y');
        // $data_extenso = $data_nota->translatedFormat('l, j \d\e  F \d\e Y');
        $data_extenso = $data_nota->translatedFormat('j \d\e  F \d\e Y');

        $periodo = optional($solicitacao->evento)->periodo
            ?? optional($solicitacao->atividade)->periodo;

        $programa = Programa::find($solicitacao->programa_id);

        return view($recibo, [
            'title' => 'Recibo da solicitação' . ' - ' . $solicitacao->solicitante->nome,
            'solicitacao' => $solicitacao,
            'programa' => $programa,
            'fontes_pagadoras' => FontePagadora::all(),
            'valor_tipos' => ValorTipo::all(),
            'statuses' => Status::all(),
            'count_notas' => $solicitacao->notas->count(),
            'valor_extenso' => ValoresHelper::valorPorExtenso($nota->valor),
            'periodo' => $periodo,
            'data_impressao' => $data_impressao,
            'data_extenso' => $data_extenso,
            'valor_total' => $valor_total,
            'tipo_valor' => \Str::upper($tipo_valor->nome),
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
