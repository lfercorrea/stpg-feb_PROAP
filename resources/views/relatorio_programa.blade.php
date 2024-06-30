@extends('layout')
@section('title', $title)
@section('content')
    <div class='center print-hidden section-margin-bottom'>
        <h5>Relatório consolidado por programas</h5>
    </div>
    <div class="print-only section-margins">
        <h6><b>Relatório consolidado de gastos em programas de pós-graduação</b></h6>
    </div>
    <form action="{{ route('site.relatorio.index') }}" method="GET" class="print-hidden">
        <div class="row">
            <div class="input-field col s6 m2">
                <input name="start_date" id="start_date" type="date" class="validate" min="1900-01-01" max="2099-12-31" value="{{ $start_time }}">
                <label for="start_date">De:</label>
            </div>
            <div class="input-field col s6 m2">
                <input name="end_date" id="end_date" type="date" class="validate" min="1900-01-01" max="2099-12-31" value="{{ $end_time }}">
                <label for="end_date">Até:</label>
            </div>
            <div class="col s6 m2 input-field">
                <select class="browser-default" name="tipo_solicitante"><option value="">Todos</option>
                    <option value="Discente">Discente</option>
                    <option value="Docente Permanente">Docente Permanente</option>
                    <option value="Docente Colaborador">Docente Colaborador</option>
                </select>
            </div>
            <div class="col s6 m2 input-field">
                <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                    <option value="" selected disabled>Programas</option>
                        @foreach ($lista_programas as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col s12 m2 input-field">
                <button class="btn waves-effect waves-light black" type="submit">Filtrar</button> 
            </div>
            <div class="col s12 m2 input-field">
                <button id="print-button" class="btn-flat waves-effect waves-black" type="button">
                    Imprimir
                    <i class="material-icons right">print</i>
                </button>
            </div>
        </div>
    </form>
    @if (($start_date && $end_date) || $tipo_solicitante || $programas_selecionados)
        <div class="row">
            <div class="search-terms small-text">
                @if ($start_date AND $end_date)
                    Período: de <b>{{ $start_date }}</b> até <b>{{ $end_date }}</b>
                    <br>
                @endif
                @if ($tipo_solicitante)
                    Tipo de solicitante: <b>{{ $tipo_solicitante }}</b>
                    <br>
                @endif
                @if ($programas_selecionados)
                    Programas: <b>{{ $programas_selecionados }}</b>
                    <br>
                @endif
            </div>
        </div>
    @endif
    @foreach ($programas as $programa)
        <h6 class="blue-text text-darken-2"><b><i>{{ $programa->nome }}</i></b> ({{ $programa->count_solicitacoes }})</h6>
        <table class="compact-table striped responsive-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Valor gasto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programa as $solicitacao)
                    @php
                        $gastos_programa += $solicitacao->soma_notas;
                    @endphp
                    <tr>
                        <td>
                            <div class="chip print-hidden">{{ ($solicitacao->tipo_solicitante) }}</div>
                            <a href="{{ route('site.solicitante.show', ['id' => $solicitacao->solicitante_id]) }}" class="hover-underline"><b>{{ Str::upper($solicitacao->solicitante_nome) }}</b></a>
                            <i>(<a href="{{ route('site.solicitacao.show', ['id' => $solicitacao->id]) }}" class="hover-underline">{{ $solicitacao->servico_tipo ?? $solicitacao->tipo }}</a>)</i>
                        </td>
                        <td>R$ {{ number_format($solicitacao->soma_notas, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="compact-table responsive-table">
            <tr>
                <th class="right-align"><span class="blue-text text-darken-2"><i><u>{{ $programa->nome }}</u></i></span></th>
            </tr>
            <tr>
                <td class="right-align"><span class="green-text text-darken-2">Saldo inicial (A): R$ {{ number_format($programa->saldo_inicial, 2, ',', '.') }}</span></th>
            </tr>
            <tr>
                <td class="right-align"><span class="red-text text-darken-2">Gastos neste relatório (B): R$ {{ number_format($gastos_programa, 2, ',', '.') }}</span></th>
            </tr>
            <tr>
                <td class="right-align"><span class="black-text text-darken-2">Saldo (A-B): R$ {{ number_format(($programa->saldo_inicial - $gastos_programa), 2, ',', '.') }}</span></th>
            </tr>
        </table>
        @php
            $total_geral += $gastos_programa;
            $gastos_programa = 0;
        @endphp
    @endforeach
    @if ($programas->count() > 1)
        <div class="container center section-margins">
            <span class="red-text text-darken-2"><h6><b>Total geral de gastos neste relatório: R$ {{ number_format($total_geral, 2, ',', '.') }}</b></h6></span>
        </div>
    @endif
    @if ($programas->count() == 0)
        <div class="container center">
            <h6><p>Nenhum dado para mostrar.</p></h6>
        </div>            
    @endif
    <div class="row center section-margins side-margins print-hidden">
        <a class="btn-small black waves-effect waves-light" onclick="history.back()">Voltar</a>
    </div>
@endsection
