@extends('layout')
@section('title', $title)
@section('content')
    <div class='center print-hidden section-margin-bottom'>
        <h5>{{ $title }}</h5>
    </div>
    <div class="print-only section-margins">
        <h6><b>Saldos iniciais dos programas</b></h6>
    </div>
    @if ($programas->count() > 0)
        <div class="center">
            <table class="compact-table striped responsive-table">
                <thead>
                    <tr>
                        <th>Programa</th>
                        <th>Coordenador</th>
                        <th>Projeto CAPES vigente</th>
                        <th>Saldo inicial</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programas as $programa)
                        <tr>
                            <td>
                                <a href="{{ route('site.programa.edit', ['id' => $programa->id]) }}" class="btn-flat waves-effect print-hidden"><i class="material-icons tiny">edit</i></a>
                                <b>{{ $programa->nome }}</b>
                            </td>
                            <td>{{ $programa->coordenador }}</td>
                            <td>{{ $programa->projeto_capes }}</td>
                            <td>{{ $brl->formatCurrency($programa->saldo_inicial, 'BRL') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="container center">
            <h6><p>Nenhum dado para mostrar.</p></h6>
            <div class="row">
                <div class="col s12 input-field">
                    <a href="{{ route('import_menu') }}" class="btn green darken-2 waves-effect waves-light">Importar CSV</a>
                </div>
            </div>
        </div>
    @endif
@endsection
