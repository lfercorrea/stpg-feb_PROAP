
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
        <div class="container">
            <div class="row">
                <div class="col s6 m3">
                    <label for="">Tipo de solicitação</label>
                    <select name="tipo_solicitacao_id" class="browser-default"><option selected disabled>Selecione</option>
                        @foreach ($tipos_solicitacao as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col s6 m3">
                    <label>Status da solicitação</label>
                    <select name="status_id" class="browser-default"><option selected disabled>Selecione</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col s12 m2">
                    <label>Itens por página</label>
                    <select name="limit" class="browser-default">
                        <option selected disabled>Selecione</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="no">Sem paginação</option>
                    </select>
                </div>
                <div class="col s6 m4 input-field">
                    <button class="btn waves-effect waves-light black" type="submit">Buscar</button>
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
                    <th>Resumo da solicitação</th>
                    <th class="print-hidden">Observação</th>
                    <th>Valores solicitados</th>
                    <th>Valores pagos</th>
                    <th class="min-width-25">Solicitante</th>
                    <th class="center-align print-hidden">Conferência</th>
                    <th>Data da solicitação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitacoes as $solicitacao)
                    <tr>
                        <td>
                            <a href="{{ route('site.solicitacao.show', ['id' => $solicitacao->id]) }}"><b>{{ optional($solicitacao->servico_tipo)->nome ?? $solicitacao->tipo->nome }}:</b>
                                {{ $solicitacao->resumo }}.
                                @if ($solicitacao->periodo)
                                    Período: {{ $solicitacao->periodo }}
                                @endif
                            </a>
                        </td>
                        <td class="print-hidden"><i>{{ $solicitacao->observacao }}</i></td>
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
                                    <b>-{{ $nota->valor_tipo->nome }}:</b> {{ $brl->formatCurrency($nota->valor, 'BRL') }}
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
                        <td>
                            <a href="{{ route('site.solicitante.show', ['id' => $solicitacao->solicitante->id]) }}" class="black-text hover-underline inline-flex"><b>{{ Str::upper($solicitacao->solicitante->nome) }}</b></a>
                            <span class="print-hidden">(<a href="mailto:{{ $solicitacao->solicitante->email }}" class="hover-underline">{{ $solicitacao->solicitante->email }}</a>)</span>
                            <div>
                                <i>{{ $solicitacao->solicitante->tipo_solicitante }} da {{ $solicitacao->programa->nome }}</i>
                            </div>
                        </td>
                        <td class="center print-hidden">
                            @if ($solicitacao->site_evento)
                                <div class="small-text bold-text">
                                    <a href="{{ $solicitacao->site_evento }}" class="hover-underline" target="_blank" rel="noreferrer" title="{{ $solicitacao->site_evento }}">Site do evento</a>
                                </div>
                            @endif
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
            </tbody>
        </table>
        <div class="row">
            <div class="container">
                <div class="section-margin-top">
                    <h6>Índices das solicitações</h6>
                </div>
                <table class="bordered striped compact-table">
                    <thead>
                        <tr>
                            @foreach ($indices as $indice => $contagem)
                                <th>{{ $indice }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($indices as $indice => $contagem)
                                <td>{{ $contagem }}</td>
                            @endforeach
                            <td><b>{{ $solicitacoes->count() }}</b></td>
                        </tr>
                        @if ($total_pago > 0)
                            <tr>
                                <td colspan="10" class="center"><span class="red-text"><b>Valor total pago: {{ $brl->formatCurrency($total_pago, 'BRL') }}</b></span></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
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
