@extends('layout')
@section('title', $title)
@section('content')
    <div class="side-margins">
        <div class="row section-margin-bottom">
            <div class="col s12 print-only section-margins">
                <h6><b>Relatório de solicitações</b></h6>
            </div>
            <div class="col s12 m10">
                <h5>{{ $solicitante->nome }} (<a href="mailto:{{ $solicitante->email }}" class="hover-underline">{{ $solicitante->email }}</a>)</h5>
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
                    <h6><i><b><span class="blue-text text-darken-2">{{ $solicitacoes_programa->first()->programa->nome }}</span></b></i></h6>
                    <table class="bordered striped responsive-table highlight">
                        <thead>
                            <tr>
                                <th class="print-hidden">Status</th>
                                <th>Solicitação</th>
                                <th>Valor pago</th>
                                <th>Discriminação</th>
                                <th class="print-hidden">Orientador</th>
                                <th class="center-align print-hidden">Parecer</th>
                                <th class="center-align print-hidden">Orçamento</th>
                                <th class="center-align print-hidden">Artigo</th>
                                <th class="center-align print-hidden">Aceite</th>
                                <th>Data da solicitação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($solicitacoes_programa as $solicitacao)
                                @php
                                    $valor_total_programa += $solicitacao->soma_notas();
                                    $resumo_solicitacao = optional($solicitacao->evento)->nome
                                        ?? optional($solicitacao->atividade)->descricao
                                        ?? optional($solicitacao->material)->descricao
                                        ?? optional($solicitacao->traducao_artigo)->descricao
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
                                @endphp
                                <tr>
                                    <td class="print-hidden">{{ $solicitacao->status->nome }}</td>
                                    <td><a href="{{ route('site.solicitacao.show', ['id' => $solicitacao->id]) }}"><b>{{ $solicitacao->tipo->nome }}</b>: {{ $resumo_solicitacao }}</a></td>
                                    <td>R$&nbsp;{{ number_format($solicitacao->soma_notas(), 2, ',', '.') }}</td>
                                    <td>
                                        @foreach ($solicitacao->notas as $nota)
                                            <b>R$&nbsp;{{ number_format($nota->valor, 2, ',', '.') }}</b>&nbsp;<i>({{ $nota->valor_tipo->nome }})</i><br>
                                        @endforeach
                                    </td>
                                    <td class="print-hidden">{{ $solicitacao->nome_do_orientador }}</td>
                                    <td class="center-align print-hidden">
                                        @if ($link_parecer)
                                            <a href="{{ $link_parecer }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_parecer }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                        @endif
                                    </td>
                                    <td class="center-align print-hidden">
                                        @if ($link_orcamento)
                                            <a href="{{ $link_orcamento }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_orcamento }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                        @endif
                                    </td>
                                    <td class="center-align print-hidden">
                                        @if ($link_artigo_copia)
                                            <a href="{{ $link_artigo_copia }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_copia }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                        @endif
                                    </td>
                                    <td class="center-align print-hidden">
                                        @if ($link_artigo_aceite)
                                            <a href="{{ $link_artigo_aceite }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_aceite }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                        @endif
                                    </td>
                                    <td>{{ $solicitacao->carimbo_data_hora }}</td>
                                </tr>
                            @endforeach
                            {{-- <table class="bordered compact-table striped responsive-table"> --}}
                                <tr>
                                    <th colspan="10" class="center-align blue-text text-darken-2">Total na {{ $solicitacoes_programa->first()->programa->nome }}:&nbsp;R$&nbsp;{{ number_format($valor_total_programa, 2, ',', '.') }}</th>
                                </tr>
                            {{-- </table> --}}
                        </tbody>
                    </table>
                    @php
                        $valor_total += $valor_total_programa;
                        $valor_total_programa = 0;
                    @endphp
                @endforeach
                <div class="container center section-margins">
                    <span class="red-text text-darken-2"><h6><b>Total geral pago:&nbsp;R$&nbsp;{{ number_format($valor_total, 2, ',', '.') }}</b></h6></span>
                </div>
            </div>
        @endif
        <div class="container center print-hidden">
            <a class="btn-flat waves-effect waves-black" onclick="history.back()">Voltar</a>
            <a class="btn black waves-effect waves-light" href="{{ route('site.solicitacoes.index') }}">Todas as solicitações</a>
        </div>
    </div>
@endsection