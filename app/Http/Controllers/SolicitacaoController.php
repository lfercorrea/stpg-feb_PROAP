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
use App\Models\FontePagadora;
use App\Models\Nota;

class SolicitacaoController extends Controller
{
    public function index(Request $request) {
        $count_solicitacoes = 0;

        if($request->has('search') OR $request->has('programa_id') OR $request->has('tipo_solicitacao_id')){
            $solicitacoes = Solicitacao::search($request->search, $request->programa_id, $request->tipo_solicitacao_id)
                ->orderByRaw("STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC")
                ->paginate(30);
            $count_solicitacoes = Solicitacao::search($request->search, $request->programa_id, $request->tipo_solicitacao_id)->count();
        }
        else{
            $solicitacoes = Solicitacao::with([
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

        $vars = [
            'page_title' => 'Estoque',
            'solicitacoes' => $solicitacoes,
            'count_solicitacoes' => $count_solicitacoes,
            'search_term' => $request->search,
            'search_programa_id' => $request->programa_id,
            'search_tipo_solicitacao_id' => $request->tipo_solicitacao_id,
            'tipos_solicitacao' => SolicitacaoTipo::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'programas' => Programa::orderBy('nome', 'asc')->pluck('nome', 'id')->toArray(),
            'solicitacoes' => $solicitacoes,
        ];
        
        return view('solicitacoes', $vars);
    }
    

    public function show(string $id) {
        $solicitacao = Solicitacao::where('id', $id)
            ->with([
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
        ]);
    }


    public function lancar_nota(Request $request, $id) {
        $nota = new Nota();

        $request->validate([
            'numero' => 'required|string|max:255',
            'data' => 'required|string',
            'descricao' => 'nullable|string',
            // 'valor' => 'regex:/^\d+(\,\d{1,2})?$/',
            'valor' => 'required|numeric',
            'fonte_pagadora_id' => 'required|integer',
        ], [
            'numero.required' => 'Deve ser fornecido um número/código para a nota/recibo',
            'numero.string' => 'O número ou código da nota/rebico deve ser uma string',
            'numero.max' => 'O número/código da nota não pode passar de 255 caracteres',
            'data.required' => 'A data da nota precisa ser informada',
            'data.string' => 'A data da nota precisa ser uma string',
            'descricao.string' => 'A descrição precisa ser uma string',
            'valor.required' => 'O valor precisa ser informado',
            'valor.numeric' => 'O valor precisa ser numérico',
            'fonte_pagadora_id.required' => 'A fonte pagadora deve ser informada pelo campo',
            'fonte_pagadora_id.integer' => 'A fonte pagadora deve ser do tipo INT',
        ]);

        $nota->numero = $request->numero;
        $nota->data = $request->data;
        $nota->descricao = $request->descricao;
        $nota->valor = $request->valor;
        $nota->fonte_pagadora_id = $request->fonte_pagadora_id;
        $nota->solicitacao_id = $id;

        $nota->save();
        
        return redirect()
            ->route('site.solicitacao', ['id' => $id])
            ->with('success', 'Nota lançada.');
    }
}
