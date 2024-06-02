@extends('layout')
@section('content')
    <div class="side-margins">
        <div class="row section-margins">
            <div class="print-only section-margins">
                <h6><b>Detalhamento do solicitante</b></h6>
            </div>
            <div class="col s12 m6">
                <h6>{{ $solicitante->nome }} ({{ $solicitante->email }})</h6>
            </div>
            <div class="col s12 m6 right-align">
            <button id="print-button" class="btn-flat waves-effect waves-light print-hidden">
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
                <h6>Solicitações</h6>
            </div>
            <div class="row">
                <table class="bordered striped responsive-table highlight">
                    <thead>
                        <tr>
                            <th class=" print-hidden">Status</th>
                            <th>Solicitação</th>
                            <th>Programa</th>
                            <th>Valor pago</th>
                            <th class="print-hidden">Orientador</th>
                            <th class="center-align print-hidden">Parecer</th>
                            <th class="center-align print-hidden">Orçamento</th>
                            <th class="center-align print-hidden">Artigo</th>
                            <th class="center-align print-hidden">Aceite</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitacoes as $solicitacao)
                        <tr>
                            @php
                                $total_valor_pago =+ $solicitacao->soma_notas();
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
                            <td class=" print-hidden">{{ $solicitacao->status->nome }}</td>
                            <td><a href="{{ route('site.solicitacao', ['id' => $solicitacao->id]) }}"><b>{{ $solicitacao->tipo->nome }}</b>: {{ $resumo_solicitacao }}</td>
                            <td>{{ $solicitacao->programa->nome }}</td>
                            <td>R$&nbsp;{{ number_format($solicitacao->soma_notas(), 2, ',', '.') }}</td>
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
                    </tbody>
                </table>
                <table class="bordered compact-table striped responsive-table">
                    <tr>
                        <th class="center-align"><span class="blue-text text-darken-2">Total pago:&nbsp;R$&nbsp;{{ number_format($total_valor_pago, 2, ',', '.') }}</span></th>
                    </tr>
                </table>
            </div>
        @endif
        <div class="container center print-hidden">
            <a class="btn-flat waves-effect waves-black" onclick="history.back()">Voltar</a>
            <a class="btn black waves-effect waves-black" href="{{ route('site.solicitacoes') }}">Todas as solicitações</a>
        </div>
    </div>
@endsection