
@extends('layout')
@section('content')
    <div class='center'>
        <h5>Solicitações</h5>
        <hr>
    </div>
    <form action="#" method="GET">
        <div class="row">
            <div class="col s12 m2 input-field">
                <a href="{{ route('import_menu') }}" class="btn green waves-effect waves-light">Importar CSV</a>
            </div>
            <div class="col s12 m3 input-field">
                <input type="text" name="search" placeholder="Buscar"> 
            </div>
            <div class="col s8 m2 input-field">
                <select class="browser-default" name="tipo_solicitacao_id"><option value="">Tipo</option>
                    @foreach ($tipos_solicitacao as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col s8 m2 input-field">
                <select class="browser-default" name="status_id"><option value="">Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col s4 m3 input-field">
                <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                    <option value="" selected disabled>Programas</option>
                        @foreach ($programas as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="container center">
            <button class="btn waves-effect waves-light black" type="submit">Buscar</button> 
        </div>
    </form>
    @if ($count_solicitacoes > 0)
        <div class="row">
            {!! $search_message !!}
        </div>
    @endif
    {{
        $solicitacoes->appends(request()->only(['search', 'programa_id', 'tipo_solicitacao_id', 'status_id']))
            ->links('common/pagination')
    }}
    @if (count($solicitacoes) > 0)
        <table class="bordered striped responsive-table highlight">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Tipo de solicitação</th>
                    <th>Solicitante</th>
                    <th>Programa</th>
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
                        $link_artigo_aceite = optional($solicitacao->evento)->artigo_aceite;
                        $link_artigo_copia = optional($solicitacao->evento)->artigo_copia;
                        $link_parecer = optional($solicitacao->evento)->parecer_orientador 
                            ?? optional($solicitacao->atividade)->parecer_orientador 
                            ?? optional($solicitacao->material)->parecer_orientador 
                            ?? optional($solicitacao->traducao_artigo)->parecer_orientador;
                        $link_orcamento = optional($solicitacao->evento)->orcamento_passagens 
                            ?? optional($solicitacao->atividade)->orcamento_passagens 
                            ?? optional($solicitacao->material)->orcamento 
                            ?? optional($solicitacao->traducao_artigo)->orcamento
                            ?? optional($solicitacao->outro_servico)->orcamento
                            ?? optional($solicitacao->manutencao)->orcamento;
                    @endphp
                    <td>{{ $solicitacao->status->nome }}</td>
                    <td><a href="{{ route('site.solicitacao', ['id' => $solicitacao->id]) }}" class="hover-underline"><b>{{ $solicitacao->tipo->nome }}</b></a></td>
                    <td><div class="chip">{{ $solicitacao->solicitante->tipo_solicitante }}</div><a href="{{ route('site.solicitante', ['id' => $solicitacao->solicitante->id]) }}" class="black-text hover-underline"><b>{{ Str::upper($solicitacao->solicitante->nome) }}</b></a> (<a href="mailto:{{ $solicitacao->solicitante->email }}" class="hover-underline">{{ $solicitacao->solicitante->email }}</a>)</td>
                    <td>{{ $solicitacao->programa->nome }}</td>
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
    @else
        <div class="container center">
            <h6><p>Nenhuma solicitacão encontrada.</p></h6>
        </div>
    @endif
    {{
        $solicitacoes->appends(request()->only(['search', 'programa_id', 'tipo_solicitacao_id', 'status_id']))
            ->links('common/pagination')
    }}
@endsection
