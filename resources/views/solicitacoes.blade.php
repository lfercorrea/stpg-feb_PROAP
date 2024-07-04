
@extends('layout')
@section('title', $title)
@section('content')
    <div class='center print-hidden section-margin-bottom'>
        <h5>{{ $title }}</h5>
    </div>
    <div class="print-only section-margins">
        <h6><b>Resumo das solicitações</b></h6>
    </div>
    <form action="#" method="GET" class="print-hidden">
        <div class="row">
            <div class="col s12 m2 input-field">
                <a href="{{ route('import_menu') }}" class="btn green darken-2 waves-effect waves-light">Importar CSV</a>
            </div>
            <div class="col s12 m3 input-field">
                <input type="text" name="search" placeholder="Solicitante, email, descrição da solicitação, etc."> 
            </div>
            <div class="col s6 m2 input-field">
                <input name="start_date" id="start_date" type="date" class="validate" min="1900-01-01" max="2099-12-31">
                <label for="start_date">De:</label>
            </div>
            <div class="col s6 m2 input-field">
                <input name="end_date" id="end_date" type="date" class="validate" min="1900-01-01" max="2099-12-31">
                <label for="end_date">Até:</label>
            </div>
            <div class="col s12 m3 input-field">
                <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                    <option value="" selected disabled>Programas</option>
                        @foreach ($programas as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                </select>
            </div>
        </div>
        <div class="container center">
            <div class="row center">
                <div class="col s6 m3">
                    <select name="tipo_solicitacao_id"><option value="">Tipo</option>
                        @foreach ($tipos_solicitacao as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col s6 m3">
                    <select name="status_id"><option value="">Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col s12 m2">
                    <select name="limit">
                            <option value="30">Itens por página</option>
                            <option value="100">100</option>
                            <option value="1000">1000</option>
                    </select>
                </div>
                <div class="col s6 m2">
                    <button class="btn waves-effect waves-light black" type="submit">Buscar</button>
                </div>
                <div class="col s6 m2">
                    <button id="print-button" class="btn-flat waves-effect waves-black" type="button">
                        Imprimir
                        <i class="material-icons right">print</i>
                    </button>
                </div>
            </div>
        </div>
    </form>
    @if ($search_message)
        <div class="row">
            <div class="search-terms small-text">
                {!! $search_message !!}
            </div>
        </div>
    @endif
    <div class="print-hidden">
        {{ $solicitacoes->links('common/pagination') }}
    </div>
    @if ($count_solicitacoes > 0)
        <table class="bordered striped responsive-table highlight">
            <thead>
                <tr>
                    <th>Solicitação</th>
                    <th class="min-width-25">Solicitante</th>
                    <th>Programa</th>
                    <th class="center-align print-hidden">Parecer</th>
                    <th class="center-align print-hidden">Orçamento</th>
                    <th class="center-align print-hidden">Artigo</th>
                    <th class="center-align print-hidden">Aceite</th>
                    <th>Data</th>
                    <th class="center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitacoes as $solicitacao)
                    <tr>
                        <td><a href="{{ route('site.solicitacao.show', ['id' => $solicitacao->id]) }}"><b>{{ optional($solicitacao->servico_tipo)->nome ?? $solicitacao->tipo->nome }}:</b> {{ $solicitacao->resumo }}</a></td>
                        <td><div class="chip print-hidden">{{ $solicitacao->solicitante->tipo_solicitante }}</div><a href="{{ route('site.solicitante.show', ['id' => $solicitacao->solicitante->id]) }}" class="black-text hover-underline"><b>{{ Str::upper($solicitacao->solicitante->nome) }}</b></a> <span class="print-hidden">(<a href="mailto:{{ $solicitacao->solicitante->email }}" class="hover-underline">{{ $solicitacao->solicitante->email }}</a>)</span></td>
                        <td>{{ $solicitacao->programa->nome }}</td>
                        <td class="center-align print-hidden">
                            @if ($solicitacao->parecer_orientador)
                                <a href="{{ $solicitacao->parecer_orientador }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $solicitacao->parecer_orientador }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td class="center-align print-hidden">
                            @if ($solicitacao->orcamento)
                                <a href="{{ $solicitacao->orcamento }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $solicitacao->orcamento }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td class="center-align print-hidden">
                            @if ($solicitacao->artigo_copia)
                                <a href="{{ $solicitacao->artigo_copia }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $solicitacao->artigo_copia }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td class="center-align print-hidden">
                            @if ($solicitacao->artigo_aceite)
                                <a href="{{ $solicitacao->artigo_aceite }}" class="btn-flat waves-effect" target="_blank" rel="noreferrer" title="{{ $solicitacao->artigo_aceite }}"><i class="tiny material-icons black-text">open_in_new</i></a>
                            @endif
                        </td>
                        <td>{{ $solicitacao->carimbo_data_hora }}</td>
                        <td class="center">
                            {{ $solicitacao->status->nome }}
                            @if ($soma_notas = ($solicitacao->soma_notas() > 0))
                                <br>
                                @php
                                    $total_pago += $soma_notas;
                                @endphp
                                (R$&nbsp;{{ number_format($soma_notas, 2, ',', '.') }})
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($total_pago > 0)
                    <tr>
                        <td colspan="10" class="center"><span class="red-text"><b>Valor total pago: R$ {{ number_format($total_pago, 2, ',', '.') }}</b></span></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        <div class="container center">
            <h6><p>Nenhuma solicitacão encontrada.</p></h6>
        </div>
    @endif
    <div class="print-hidden">
        {{ $solicitacoes->links('common/pagination') }}
    </div>
    <div class="row center section-margins side-margins print-hidden">
        <a class="btn-small black waves-effect waves-light" onclick="history.back()">Voltar</a>
    </div>
@endsection
