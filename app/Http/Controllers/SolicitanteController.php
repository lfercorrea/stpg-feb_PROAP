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
                ->orderBy('nome', 'asc')
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
        $solicitante = Solicitante::where('id', $id)->first();
        $solicitacoes = Solicitacao::with([
            'notas.valor_tipo',
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
        ->orderByRaw("datetime(
                substr(carimbo_data_hora, 7, 4) || '-' || 
                substr(carimbo_data_hora, 4, 2) || '-' || 
                substr(carimbo_data_hora, 1, 2) || ' ' || 
                substr(carimbo_data_hora, 12, 8)
            ) DESC"
        )
        ->get()
        ->groupBy('programa_id');
        
        $valor_total_programa = 0;
        $valor_total = 0;
        
        foreach($solicitacoes as $programa_id => $solicitacoes_programa) {
            $solicitacoes_programa->nome_programa = $solicitacoes_programa->first()->programa->nome;
            foreach($solicitacoes_programa as $solicitacao) {
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
                $soma_notas = $solicitacao->soma_notas();
                $valor_total_programa += $soma_notas;
                $solicitacao->soma_notas = number_format($soma_notas, 2, ',' ,'.');
                foreach($solicitacao->notas as $nota) {
                    $nota->valor = number_format($nota->valor, 2, ',', '.');
                }
            }

            $valor_total += $valor_total_programa;
            $solicitacoes_programa->valor_total = number_format($valor_total_programa, 2, ',', '.');
            $valor_total_programa = 0;
        }

        $solicitacoes->valor_total = number_format($valor_total, 2, ',', '.');
        
        return view('solicitante', [
            'title' => 'Solicitante' . ' - ' . $solicitante->nome,
            'solicitante' => $solicitante,
            'solicitacoes' => $solicitacoes,
        ]);
    }
}
