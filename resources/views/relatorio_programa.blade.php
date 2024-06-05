
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
    @foreach ($solicitantes_por_programa as $programa => $solicitantes)
        <h6 class="blue-text text-darken-2"><b>{{ $programa }}</b> ({{ $solicitantes->count() }})</h6>
        <table class="compact-table striped responsive-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Valor gasto</th>
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
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <table class="compact-table striped responsive-table">
            <tr>
                <th class="center-align"><span class="blue-text text-darken-2">Total {{ $programa }}:&nbsp;R$&nbsp;{{ number_format($total_programa, 2, ',', '.') }}</span></th>
            </tr>
        </table>
        @php
            $total_geral += $total_programa;
            $total_programa = 0;
        @endphp
    @endforeach
    @if (count($solicitantes_por_programa) > 1)
        <div class="container center section-margins">
            <span class="red-text text-darken-2"><h6><b>Total geral:&nbsp;R$&nbsp;{{ number_format($total_geral, 2, ',', '.') }}</b></h6></span>
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
