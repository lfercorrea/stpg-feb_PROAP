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
        $solicitacoes = ImportacoesDiscentes::orderBy('id', 'asc')->get();
        $vazia = ImportacoesDiscentes::count() === 0;

        return view('importacoes', [
            'solicitacoes' => $solicitacoes,
            'vazia' => $vazia,
        ]);
    }

    // public function drop_importacoes_discentes() {
    //     ImportacoesDiscentes::truncate();

    //     return redirect()->route('site.index')->with('success', 'Já era. Tabela de importações foi para Caixa prego.');
    // }

    /**
     * mostra a página de visualização com dados do solicitante
     */
    public function solicitante(string $id) {
        return view('solicitante', [
            'solicitante' => Solicitante::where('id', $id)->first(),
            'solicitacoes' => Solicitacao::with([
                'tipo',
                'solicitante',
                'programa',
                'programaCategoria',
                'atividade',
                'evento',
                'material',
                'servico',
            ])
            ->where('solicitante_id', $id)
            ->orderByRaw(" STR_TO_DATE(carimbo_data_hora, '%d/%m/%Y %H:%i:%s') DESC ")
            ->get(),
        ]);
    }
}
