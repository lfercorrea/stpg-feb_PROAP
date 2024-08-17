<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitacao;
use App\Models\Solicitante;
use Carbon\Carbon;

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

    public function edit(string $id) {
        $solicitante = Solicitante::findOrFail($id);
        $solicitante->rg_data_expedicao = Carbon::createFromFormat('d/m/Y', $solicitante->rg_data_expedicao)->format('Y-m-d');
        $solicitante->nascimento = Carbon::createFromFormat('d/m/Y', $solicitante->nascimento)->format('Y-m-d');

        return view('solicitante_edit', [
            'title' => 'Alterar dados do solicitante' . ' - ' . $solicitante->nome,
            'solicitante' => $solicitante,
        ]);
    }

    public function store(Request $request) {
        $regras = [
            'nome' => 'required|string|max:255',
            'tipo_solicitante' => 'required|string|max:255',
            'cpf' => 'required|string|max:255',
            'rg' => 'required|string|max:255',
            'rg_data_expedicao' => 'required|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
            'rg_orgao_expedidor' => 'required|string|max:255',
            'nascimento' => 'required|date_format:Y-m-d|after:1900-01-01|before:2099-12-31',
            'endereco_completo' => 'required|string',
            'telefone' => 'required|string|max:255',
            'banco' => 'required|string|max:255',
            'banco_agencia' => 'required|string|max:255',
            'banco_conta' => 'required|string|max:255',
        ];

        $mensagens_erro = [
            'nome.required' => 'O nome precisa ser preenchido.',
            'nome.string' => 'O nome precisa ser uma string.',
            'nome.max' => 'O nome pode ter, no máximo, 255 caracteres.',
            'tipo_solicitante.required' => 'O tipo de solicitante precisa ser selecionado.',
            'tipo_solicitante.string' => 'O tipo de solicitante precisa ser uma string.',
            'tipo_solicitante.max' => 'O tipo de solicitante precisa ter, no máximo, 255 caracteres.',
            'cpf.required' => 'O CPF precisa ser informado.',
            'cpf.string' => 'O CPF precisa ser uma string.',
            'cpf.max' => 'O CPF precisa ter, no máximo, 255 caracteres.',
            'rg.required' => 'O RG precisa ser informado.',
            'rg.string' => 'O RG precisa ser uma string.',
            'rg.max' => 'O RG precisa ter, no máximo, 255 caracteres.',
            'rg_data_expedicao.required' => 'A data de expedicao do RG precisa ser informada.',
            'rg_data_expedicao.date_format' => 'A data de expedicao do RG está num formato incorreto.',
            'rg_data_expedicao.after' => 'A data de expedicao do RG deve ser posterior a 01/01/1900.',
            'rg_data_expedicao.before' => 'A data de expedicao do RG deve ser anterior a 31/12/2099.',
            'rg_orgao_expedidor.required' => 'O órgão expedidor do RG precisa ser informado.',
            'rg_orgao_expedidor.string' => 'O órgão expedidor do RG precisa ser uma string.',
            'rg_orgao_expedidor.max' => 'O órgão expedidor do RG precisa ter, no máximo, 255 caracteres.',
            'nascimento.required' => 'A data de nascimento precisa ser informada.',
            'nascimento.date_format' => 'A data de nascimento está num formato incorreto.',
            'nascimento.after' => 'A data de nascimento deve ser posterior a 01/01/1900.',
            'nascimento.before' => 'A data de nascimento deve ser anterior a 31/12/2099.',
            'endereco_completo.required' => 'O endereço precisa ser informado.',
            'endereco_completo.string' => 'O endereço precisa ser uma string.',
            'telefone.required' => 'O telefone precisa ser informado.',
            'telefone.string' => 'O telefone precisa ser uma string.',
            'telefone.max' => 'O telefone precisa ter, no máximo, 255 caracteres.',
            'banco.required' => 'O banco precisa ser informado.',
            'banco.string' => 'O banco precisa ser uma string.',
            'banco.max' => 'O banco precisa ter, no máximo, 255 caracteres.',
            'banco_agencia.required' => 'O código da agência precisa ser informado.',
            'banco_agencia.string' => 'O código da agência precisa ser uma string.',
            'banco_agencia.max' => 'O código da agência precisa ter, no máximo, 255 caracteres.',
            'banco_conta.required' => 'O número da conta precisa ser informado.',
            'banco_conta.string' => 'O número da conta precisa ser uma string.',
            'banco_conta.max' => 'O número da conta precisa ter, no máximo, 255 caracteres.',
        ];

        $request->validate($regras, $mensagens_erro);

        $solicitante = Solicitante::findOrFail($request->id);
        $arr_request = $request->all();
        $arr_request['email'] = $solicitante->email; // para prevenir a alteração mesmo usando console do navegador
        $arr_request['nascimento'] = Carbon::createFromFormat('Y-m-d', $request->input('nascimento'))->format('d/m/Y');
        $arr_request['rg_data_expedicao'] = Carbon::createFromFormat('Y-m-d', $request->input('rg_data_expedicao'))->format('d/m/Y');
        $solicitante->update($arr_request);

        return redirect()->route('site.solicitantes.index')->with('success', 'Informações do solicitante atualizadas.');
    }
}
