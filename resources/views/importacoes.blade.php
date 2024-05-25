@extends('layout')

@section('content')
    @if (!$vazia)
        <div class="section-margins side-margins">
            <h5>Solicitações importadas via CSV</h5>
            <p>
                Os dados desta tabela são crus e esta tabela só serve para conferir que a importação deu certo.
                Pode esvaziar esta tabela sem medo de ser feliz.
            </p>
        </div>
        <div class="row center scrollable-table">
            <table class="striped responsive-table">
                <thead>
                    <tr>
                        <th>n°</th>
                        <th>status</th>
                        <th>carimbo_data_hora</th>
                        <th>email</th>
                        <th>programa</th>
                        <th>categoria</th>
                        <th>nome</th>
                        <th>cpf</th>
                        <th>rg</th>
                        <th>rg_data_expedicao</th>
                        <th>rg_orgao_expedidor</th>
                        <th>nascimento</th>
                        <th>endereco_completo</th>
                        <th>telefone</th>
                        <th>banco</th>
                        <th>banco_agencia</th>
                        <th>banco_conta</th>
                        <th>tipo_solicitacao</th>
                        <th>evento_nome</th>
                        <th>evento_local</th>
                        <th>evento_periodo</th>
                        <th>evento_site_evento</th>
                        <th>evento_titulo_trabalho</th>
                        <th>evento_forma_participacao</th>
                        <th>evento_valor_inscricao</th>
                        <th>evento_valor_passagens</th>
                        <th>evento_valor_diarias</th>
                        <th>evento_justificativa</th>
                        <th>evento_ja_solicitou_recurso</th>
                        <th>evento_artigo_copia</th>
                        <th>evento_artigo_aceite</th>
                        <th>evento_parecer_orientador</th>
                        <th>evento_orcamento_passagens</th>
                        <th>material_descricao</th>
                        <th>material_valor</th>
                        <th>material_justificativa</th>
                        <th>material_ja_solicitou_recurso</th>
                        <th>material_orcamento</th>
                        <th>material_parecer_orientador</th>
                        <th>servico_tipo</th>
                        <th>servico_titulo_artigo</th>
                        <th>servico_valor</th>
                        <th>servico_justificativa</th>
                        <th>servico_artigo_a_traduzir</th>
                        <th>servico_orcamento</th>
                        <th>servico_parecer_orientador</th>
                        <th>atividade_descricao</th>
                        <th>atividade_local</th>
                        <th>atividade_periodo</th>
                        <th>atividade_valor_diarias</th>
                        <th>atividade_valor_passagens</th>
                        <th>atividade_justificativa</th>
                        <th>atividade_carta_convite</th>
                        <th>atividade_parecer_orientador</th>
                        <th>atividade_orcamento_passagens</th>
                        <th>atividade_nome_orientador</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($solicitacoes as $solicitacao)
                        <tr>
                            <td>{{ $solicitacao->id }}</td>
                            <td>{{ $solicitacao->status }}</td>
                            <td>{{ $solicitacao->carimbo_data_hora }}</td>
                            <td>{{ $solicitacao->email }}</td>
                            <td>{{ $solicitacao->programa }}</td>
                            <td>{{ $solicitacao->categoria }}</td>
                            <td>{{ $solicitacao->nome }}</td>
                            <td>{{ $solicitacao->cpf }}</td>
                            <td>{{ $solicitacao->rg }}</td>
                            <td>{{ $solicitacao->rg_data_expedicao }}</td>
                            <td>{{ $solicitacao->rg_orgao_expedidor }}</td>
                            <td>{{ $solicitacao->nascimento }}</td>
                            <td>{{ $solicitacao->endereco_completo }}</td>
                            <td>{{ $solicitacao->telefone }}</td>
                            <td>{{ $solicitacao->banco }}</td>
                            <td>{{ $solicitacao->banco_agencia }}</td>
                            <td>{{ $solicitacao->banco_conta }}</td>
                            <td>{{ $solicitacao->tipo_solicitacao }}</td>
                            <td>{{ $solicitacao->evento_nome }}</td>
                            <td>{{ $solicitacao->evento_local }}</td>
                            <td>{{ $solicitacao->evento_periodo }}</td>
                            <td>{{ $solicitacao->evento_site_evento }}</td>
                            <td>{{ $solicitacao->evento_titulo_trabalho }}</td>
                            <td>{{ $solicitacao->evento_forma_participacao }}</td>
                            <td>{{ $solicitacao->evento_valor_inscricao }}</td>
                            <td>{{ $solicitacao->evento_valor_passagens }}</td>
                            <td>{{ $solicitacao->evento_valor_diarias }}</td>
                            <td>{{ $solicitacao->evento_justificativa }}</td>
                            <td>{{ $solicitacao->evento_ja_solicitou_recurso }}</td>
                            <td>{{ $solicitacao->evento_artigo_copia }}</td>
                            <td>{{ $solicitacao->evento_artigo_aceite }}</td>
                            <td>{{ $solicitacao->evento_parecer_orientador }}</td>
                            <td>{{ $solicitacao->evento_orcamento_passagens }}</td>
                            <td>{{ $solicitacao->material_descricao }}</td>
                            <td>{{ $solicitacao->material_valor }}</td>
                            <td>{{ $solicitacao->material_justificativa }}</td>
                            <td>{{ $solicitacao->material_ja_solicitou_recurso }}</td>
                            <td>{{ $solicitacao->material_orcamento }}</td>
                            <td>{{ $solicitacao->material_parecer_orientador }}</td>
                            <td>{{ $solicitacao->servico_tipo }}</td>
                            <td>{{ $solicitacao->servico_titulo_artigo }}</td>
                            <td>{{ $solicitacao->servico_valor }}</td>
                            <td>{{ $solicitacao->servico_justificativa }}</td>
                            <td>{{ $solicitacao->servico_artigo_a_traduzir }}</td>
                            <td>{{ $solicitacao->servico_orcamento }}</td>
                            <td>{{ $solicitacao->servico_parecer_orientador }}</td>
                            <td>{{ $solicitacao->atividade_descricao }}</td>
                            <td>{{ $solicitacao->atividade_local }}</td>
                            <td>{{ $solicitacao->atividade_periodo }}</td>
                            <td>{{ $solicitacao->atividade_valor_diarias }}</td>
                            <td>{{ $solicitacao->atividade_valor_passagens }}</td>
                            <td>{{ $solicitacao->atividade_justificativa }}</td>
                            <td>{{ $solicitacao->atividade_carta_convite }}</td>
                            <td>{{ $solicitacao->atividade_parecer_orientador }}</td>
                            <td>{{ $solicitacao->atividade_orcamento_passagens }}</td>
                            <td>{{ $solicitacao->atividade_nome_orientador }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="container center">
            <form action="{{ route('site.drop_importacoes_discentes') }}" method="POST">
            @csrf
                <button type="submit" class="btn black waves-effect waves-light">Esvaziar tabela de importacoes</button>
            </form>
        </div>
    @else
        <div class="row center section-margins side-margins">
            <h5>A tabela de importações tá vazia.</h5>
        </div>
    @endif
@endsection