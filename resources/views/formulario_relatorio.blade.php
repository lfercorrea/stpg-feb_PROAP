
@extends('layout')
@section('content')
    <div class='center'>
        <h5>Relatório de gastos</h5>
        <hr>
    </div>
    <form action="{{ route('site.relatorio.index') }}" method="GET" class="print-hidden">
        <div class="container center">
            <div class="row">
                {{-- <div class="col s12 m3 input-field">
                    <input type="text" name="search" placeholder="Filtro"> 
                </div> --}}
                <div class="col s8 m4 input-field">
                    <select class="browser-default" name="tipo_solicitante"><option value="">Todos</option>
                        <option value="Discente">Discente</option>
                        <option value="Docente Permanente">Docente Permanente</option>
                        <option value="Docente Colaborador">Docente Colaborador</option>
                    </select>
                </div>
                {{-- <div class="col s8 m2 input-field">
                    <select class="browser-default" name="tipo_solicitacao_id"><option value="">Tipo</option>
                        @foreach ($tipos_solicitacao as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- <div class="col s8 m2 input-field">
                    <select class="browser-default" name="status_id"><option value="">Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->nome }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col s4 m4 input-field">
                    <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                        <option value="" selected disabled>Programas</option>
                            @foreach ($programas as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="col s4 m4 input-field">
                    <button class="btn waves-effect waves-light black" type="submit">Filtrar</button> 
                </div>
            </div>
        </div>
    </form>
    {{-- @if ($count_solicitacoes > 0)
        <div class="row">
            {!! $search_message !!}
        </div>
    @endif --}}
    {{-- {{
        $solicitacoes->appends(request()->only(['search', 'programa_id', 'tipo_solicitacao_id', 'status_id']))
            ->links('common/pagination')
    }} --}}
    {{-- @if (count($solicitacoes) > 0) --}}
        @foreach ($solicitantes_por_programa as $programa => $solicitantes)
            <h5>{{ $programa }} ({{ $solicitantes->count() }})</h5>
            <table class="compact-table striped responsive-table">
            {{-- <table class="bordered striped responsive-table highlight"> --}}
                <thead>
                    <tr>
                        <th>Solicitante</th>
                        {{-- <th>Solicitação</th> --}}
                        <th>Valor gasto</th>
                        {{-- <th>Programa</th> --}}
                        {{-- <th>Data</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($solicitantes as $solicitante)
                        @if ($solicitante->soma_notas() > 0)
                        @php
                            $total_programa += $solicitante->soma_notas()
                        @endphp
                        <tr>
                            <td><a href="{{ route('site.solicitante', ['id' => $solicitante->id]) }}" class="hover-underline"><b>{{ Str::upper($solicitante->nome) }}</b></a>{{ ($solicitante->tipo_solicitante) ? ' (' . $solicitante->tipo_solicitante . ')' : '' }}</td>
                            <td>R$&nbsp;{{ number_format($solicitante->soma_notas(), 2, ',', '.') }}</td>
                            {{-- <td>{{ $solicitante->solicitacao()->first()->programa->nome }}</td> --}}
                            {{-- <td></td> --}}
                        </tr>
                        @endif
                    @endforeach
                    {{-- @foreach ($solicitacoes as $solicitacao)
                    <tr>
                        <td>{{ $solicitacao->status->nome }}</td>
                        <td><a href="{{ route('site.solicitacao', ['id' => $solicitacao->id]) }}" class="hover-underline"><b>{{ optional($solicitacao->servico_tipo)->nome ?? $solicitacao->tipo->nome }}</b></a></td>
                        <td><div class="chip">{{ $solicitacao->solicitante->tipo_solicitante }}</div><a href="{{ route('site.solicitante', ['id' => $solicitacao->solicitante->id]) }}" class="black-text hover-underline"><b>{{ Str::upper($solicitacao->solicitante->nome) }}</b></a> (<a href="mailto:{{ $solicitacao->solicitante->email }}" class="hover-underline">{{ $solicitacao->solicitante->email }}</a>)</td>
                        <td>{{ $solicitacao->programa->nome }}</td>
                        <td>{{ $solicitacao->carimbo_data_hora }}</td>
                    </tr>
                    @endforeach --}}
                </tbody>
            </table>
            <table class="compact-table striped responsive-table">
                <tr>
                    <th class="center-align"><span class="blue-text darken-5">Total {{ $programa }}:&nbsp;R$&nbsp;{{ number_format($total_programa, 2, ',', '.') }}</span></th>
                </tr>
            </table>
            @php
                $total_programa = 0
            @endphp
        @endforeach
        @if ($solicitantes_por_programa->count() == 0)
            <div class="container center">
                <h6><p>Nenhum dado para mostrar.</p></h6>
            </div>            
        @endif
    {{-- @else
        <div class="container center">
            <h6><p>Nenhuma solicitacão encontrada.</p></h6>
        </div>
    @endif --}}
    {{-- {{
        $solicitacoes->appends(request()->only(['search', 'programa_id', 'tipo_solicitacao_id', 'status_id']))
            ->links('common/pagination')
    }} --}}
@endsection
