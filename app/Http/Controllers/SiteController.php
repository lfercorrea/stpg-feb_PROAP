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
                'nota',
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
                ->get(),
            'total_valor_pago' => 0,
        ]);
    }
}
