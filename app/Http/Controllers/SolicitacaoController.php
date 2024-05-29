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

class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            // $count_solicitacoes = Solicitacao::with([
            //     'tipo',
            //     'solicitante',
            //     'programa',
            //     'programaCategoria',
            //     'atividade',
            //     'evento',
            //     'material',
            //     'servico',
            // ])->count();
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

    /**
     * Display the specified resource.
     */
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
            ])->first();

        return view('solicitacao', [
            'solicitacao' => $solicitacao,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
