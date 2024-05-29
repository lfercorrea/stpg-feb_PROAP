@extends('layout')
@section('content')
    <div class="side-margins">

        <div class="section-margins">
            <h5>{{ $solicitante->nome }} ({{ $solicitante->email }})</h5>
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
                <table class="bordered striped responsive-table highlight">
                    <thead>
                        <tr>
                            <th class="center-align">Ações</th>
                            <th>Solicitação</th>
                            <th>Programa</th>
                            <th>Orientador</th>
                            <th class="center-align">Parecer</th>
                            <th class="center-align">Orçamento</th>
                            <th class="center-align">Artigo</th>
                            <th class="center-align">Aceite</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitacoes as $solicitacao)
                        <tr>
                            @php
                                // resumo solicitacao
                                $resumo_solicitacao = optional($solicitacao->evento)->nome
                                    ?? optional($solicitacao->atividade)->descricao
                                    ?? optional($solicitacao->material)->descricao
                                    ?? optional($solicitacao->servico)->titulo_artigo;
                                // links
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
                            @endphp
                            <td class="center-align">
                                <a href="{{ route('site.solicitacao', ['id' => $solicitacao->id]) }}" class="btn-flat waves-effect">
                                    <i class="material-icons blue-text center">edit</i>
                                </a>
                                {{-- <button class="btn-flat waves-effect waves-red modal-trigger" data-target="confirm-delete-modal" data-target-url="/admin/excluir/solicitacao/" data-target-id="{{ $solicitacao->id }}">
                                    <i class="material-icons red-text darken-1 center">delete</i>
                                </button> --}}
                            </td>
                            <td><b>{{ $solicitacao->tipo->nome }}</b>: {{ $resumo_solicitacao }}</td>
                            <td>{{ $solicitacao->programa->nome }}</td>
                            <td>{{ $solicitacao->nome_do_orientador }}</td>
                            <td class="center-align">
                                @if ($link_parecer)
                                    <a href="{{ $link_parecer }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_parecer }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                @endif
                            </td>
                            <td class="center-align">
                                @if ($link_orcamento)
                                    <a href="{{ $link_orcamento }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_orcamento }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                @endif
                            </td>
                            <td class="center-align">
                                @if ($link_artigo_copia)
                                    <a href="{{ $link_artigo_copia }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_copia }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                @endif
                            </td>
                            <td class="center-align">
                                @if ($link_artigo_aceite)
                                    <a href="{{ $link_artigo_aceite }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $link_artigo_aceite }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                                @endif
                            </td>
                            <td>{{ $solicitacao->carimbo_data_hora }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="container center">
            <a class="btn black waves-effect waves-black" onclick="history.back()">Voltar</a>
        </div>

    </div>
@endsection