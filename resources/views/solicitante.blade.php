@extends('layout')
@section('title', $title)
@section('content')
    <div class="side-margins">
        <div class="row section-margin-bottom">
            <div class="col s12 print-only section-margins">
                <h6><b>Relatório de solicitações</b></h6>
            </div>
            <div class="col s12 m10">
                <h5>
                    {{ $solicitante->nome }}
                    (<a href="mailto:{{ $solicitante->email }}" class="hover-underline">{{ $solicitante->email }}</a>)
                    <a href="{{ route('site.solicitante.edit', ['id' => $solicitante->id]) }}" class="btn-flat waves-effect print-hidden"><i class="material-icons tiny">edit</i></a>
                </h5>
            </div>
            <div class="col s12 m2 right-align">
                <button id="print-button" class="btn-flat waves-effect waves-black print-hidden" type="button">
                    Imprimir
                    <i class="material-icons right">print</i>
                </button>
            </div>
        </div>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Endereço</th>
            </tr>
            <tr>
                <td>{{ $solicitante->endereco_completo }}</td>
            </tr>
        </table>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Nascimento</th>
                <th>CPF</th>
                <th>RG</th>
                <th>Órgão emissor</th>
                <th>Data de emissão</th>
            </tr>
            <tr>
                <td>{{ $solicitante->nascimento }}</td>
                <td>{{ $solicitante->cpf }}</td>
                <td>{{ $solicitante->rg }}</td>
                <td>{{ $solicitante->rg_orgao_expedidor }}</td>
                <td>{{ $solicitante->rg_data_expedicao }}</td>
            </tr>
        </table>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Telefone</th>
                <th>Banco</th>
                <th>Agência</th>
                <th>Conta</th>
            </tr>
            <tr>
                <td>{{ $solicitante->telefone }}</td>
                <td>{{ $solicitante->banco }}</td>
                <td>{{ $solicitante->banco_agencia }}</td>
                <td>{{ $solicitante->banco_conta }}</td>
            </tr>
        </table>
        @if (count($solicitacoes) > 0)
            <div class="section-margins">
                <h5>Solicitações</h5>
            </div>
            <div class="row">
                @foreach ($solicitacoes as $programa_id => $solicitacoes_programa)
                    <h6><i><b><span class="blue-text text-darken-2">{{ $solicitacoes_programa->nome_programa }}</span></b></i></h6>
                    <table class="bordered striped responsive-table highlight">
                        <thead>
                            <tr>
                                <th>Solicitação</th>
                                <th>Valores solicitados</th>
                                <th>Valores pagos</th>
                                <th class="print-hidden">Orientador</th>
                                <th class="center-align print-hidden">Conferência</th>
                                <th>Data da solicitação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($solicitacoes_programa as $solicitacao)
                                <tr>
                                    <td>
                                        <a href="{{ route('site.solicitacao.show', ['id' => $solicitacao->id]) }}"><b>{{ optional($solicitacao->servico_tipo)->nome ?? $solicitacao->tipo->nome }}</b>:
                                            {{ $solicitacao->resumo }}.
                                            @if ($solicitacao->periodo)
                                                Período: {{ $solicitacao->periodo }}
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        @if ($solicitacao->valor)
                                            <div>
                                                <i><b>-Valor solicitado: </b>{{ $solicitacao->valor }}</i>
                                            </div>
                                        @endif
                                        @if ($solicitacao->valor_diarias)
                                            <div>
                                                <i><b>-Diárias: </b>{{ $solicitacao->valor_diarias }}</i>
                                            </div>
                                        @endif
                                        @if ($solicitacao->valor_passagens)
                                            <div>
                                                <i><b>-Passagens: </b>{{ $solicitacao->valor_passagens }}</i>
                                            </div>
                                        @endif
                                        @if ($solicitacao->valor_inscricao)
                                            <div>
                                                <i><b>-Taxa de inscrição: </b>{{ $solicitacao->valor_inscricao }}</i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach ($solicitacao->notas as $nota)
                                            <div>
                                                <i>
                                                    <b>-{{ $nota->valor_tipo->nome }}:&nbsp;</b>{{ $brl->formatCurrency($nota->valor, 'BRL') }}
                                                </i>
                                            </div>
                                        @endforeach
                                        <div class="center">
                                            @if ($solicitacao->soma_notas > 0)
                                                <b>Total:&nbsp;{{ $brl->formatCurrency($solicitacao->soma_notas, 'BRL') }}</b>
                                            @else
                                                <b>{{ $solicitacao->status->nome }}</b>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="print-hidden">{{ $solicitacao->nome_do_orientador }}</td>
                                    <td class="center print-hidden">
                                        @if ($solicitacao->parecer_orientador)
                                            <div class="small-text bold-text">
                                                <a href="{{ $solicitacao->parecer_orientador }}" class="hover-underline" target="_blank" rel="noreferrer" title="{{ $solicitacao->parecer_orientador }}">Parecer do orientador</a>
                                            </div>
                                        @endif
                                        @if ($solicitacao->orcamento)
                                        <div class="small-text bold-text">
                                            <a href="{{ $solicitacao->orcamento }}" class="hover-underline" target="_blank" rel="noreferrer" title="{{ $solicitacao->orcamento }}">Orçamento</a>
                                        </div>
                                        @endif
                                        @if ($solicitacao->artigo_copia)
                                            <div class="small-text bold-text">
                                                <a href="{{ $solicitacao->artigo_copia }}" class="hover-underline" target="_blank" rel="noreferrer" title="{{ $solicitacao->artigo_copia }}">Cópia do artigo</a>
                                            </div>
                                        @endif
                                        @if ($solicitacao->artigo_aceite)
                                            <div class="small-text bold-text">
                                                <a href="{{ $solicitacao->artigo_aceite }}" class="hover-underline" target="_blank" rel="noreferrer" title="{{ $solicitacao->artigo_aceite }}">Aceite do artigo</a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $solicitacao->carimbo_data_hora }}</td>
                                </tr>
                            @endforeach
                                <tr>
                                    <th colspan="10" class="center-align blue-text text-darken-2">Total na {{ $solicitacoes_programa->first()->programa->nome }}: {{ $brl->formatCurrency($solicitacoes_programa->valor_total, 'BRL') }}</th>
                                </tr>
                        </tbody>
                    </table>
                @endforeach
                <div class="container center section-margins">
                    <span class="red-text text-darken-2"><h6><b>Total geral pago: {{ $brl->formatCurrency($solicitacoes->valor_total, 'BRL') }}</b></h6></span>
                </div>
            </div>
        @endif
        <div class="container center print-hidden">
            <a class="btn-flat waves-effect waves-black" onclick="history.back()">Voltar</a>
            <a class="btn black waves-effect waves-light" href="{{ route('site.solicitacoes.index') }}">Todas as solicitações</a>
        </div>
    </div>
@endsection