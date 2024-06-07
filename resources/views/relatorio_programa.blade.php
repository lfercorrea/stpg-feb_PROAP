@extends('layout')
@section('content')
    <div class='center print-hidden'>
        <h6>Relatório de gastos por programa</h6>
        <hr>
    </div>
    <div class="print-only section-margins">
        <h6><b>Relatório consolidado por programa</b></h6>
    </div>
    <form action="{{ route('site.relatorio.index') }}" method="GET" class="print-hidden">
        <div class="container center">
            <div class="row">
                <div class="col s8 m4 input-field">
                    <select class="browser-default" name="tipo_solicitante"><option value="">Todos</option>
                        <option value="Discente">Discente</option>
                        <option value="Docente Permanente">Docente Permanente</option>
                        <option value="Docente Colaborador">Docente Colaborador</option>
                    </select>
                </div>
                <div class="col s4 m4 input-field">
                    <select name="programa_id[]" id="programa_id" multiple="" tabindex="-1" style="display: none;">
                        <option value="" selected disabled>Programas</option>
                            @foreach ($programas as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                    </select>
                </div>
                <div class="col s4 m4 input-field">
                    <button class="btn waves-effect waves-light black" type="submit">Filtrar</button> 
                    <button id="print-button" class="btn-flat waves-effect waves-light">
                        Imprimir
                        <i class="material-icons right">print</i>
                    </button>
                </div>
            </div>
        </div>
    </form>
    @foreach ($solicitantes_por_programa as $programa_nome => $solicitacoes)
        <h6 class="blue-text text-darken-2"><b><i>{{ $programa_nome }}</i></b> ({{ $solicitacoes->count() }})</h6>
        <table class="compact-table striped responsive-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Valor gasto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitacoes as $solicitacao)
                    @php
                        $total_programa += $solicitacao['valor_gasto']
                    @endphp
                    <tr>
                        <td><a href="{{ route('site.solicitante', ['id' => $solicitacao['solicitante']->id]) }}" class="hover-underline"><b>{{ Str::upper($solicitacao['solicitante']->nome) }}</b></a>{{ ($solicitacao['solicitante']->tipo_solicitante) ? ' (' . $solicitacao['solicitante']->tipo_solicitante . ')' : '' }}</td>
                        <td>R$&nbsp;{{ number_format($solicitacao['valor_gasto'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="compact-table responsive-table">
            <tr>
                <th class="right-align"><span class="blue-text text-darken-2"><i><u>{{ $programa_nome }}</u></i></span></th>
            </tr>
            <tr>
                <td class="right-align"><span class="green-text text-darken-2">Saldo inicial: R$ {{ number_format($solicitacoes[0]['saldo_inicial'], 2, ',', '.') }}</span></th>
            </tr>
            <tr>
                <td class="right-align"><span class="red-text text-darken-2">Total de gastos: R$ {{ number_format($total_programa, 2, ',', '.') }}</span></th>
            </tr>
            <tr>
                <td class="right-align"><span class="black-text text-darken-2">Saldo restante: R$ {{ number_format($solicitacoes[0]['saldo_inicial'] - $total_programa, 2, ',', '.') }}</span></th>
            </tr>
        </table>
        @php
            $total_geral += $total_programa;
            $total_programa = 0;
        @endphp
    @endforeach
    @if (count($solicitantes_por_programa) > 1)
        <div class="container center section-margins">
            <span class="red-text text-darken-2"><h6><b>Total geral de gastos:&nbsp;R$&nbsp;{{ number_format($total_geral, 2, ',', '.') }}</b></h6></span>
        </div>
    @endif
    @if ($solicitantes_por_programa->count() == 0)
        <div class="container center">
            <h6><p>Nenhum dado para mostrar.</p></h6>
        </div>            
    @endif
    <div class="row center section-margins side-margins print-hidden">
        <a class="btn-small black waves-effect waves-black" onclick="history.back()">Voltar</a>
    </div>
@endsection
