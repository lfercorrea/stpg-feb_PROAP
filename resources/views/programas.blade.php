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
        <form action="{{ route('site.programas.update') }}" method="POST">
            @csrf
            <div class="center">
                <table class="compact-table striped responsive-table">
                    <thead>
                        <tr>
                            <th>Programa</th>
                            <th>Coordenador</th>
                            <th>Projeto CAPES</th>
                            <th>Saldo inicial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programas as $programa)
                            <tr>
                                <td>{{ $programa->nome }}</td>
                                <td>
                                    <div class="input-field col s12">
                                        <input name="coordenadores[{{ $programa->id }}]" id="coordenadores_{{ $programa->id }}" value="{{ $programa->coordenador }}" type="text" class="validate">
                                        <label for="coordenadores_{{ $programa->id }}">Nome completo do coordenador</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field col s12">
                                        <input name="projetos_capes[{{ $programa->id }}]" id="projetos_capes_{{ $programa->id }}" value="{{ $programa->projeto_capes }}" type="text" class="validate">
                                        <label for="projetos_capes_{{ $programa->id }}">Código do projeto CAPES</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-field col s12">
                                        <input name="saldos[{{ $programa->id }}]" id="saldo_inicial_{{ $programa->id }}" value="{{ $programa->saldo_inicial }}" type="number" min="0" step="0.01" class="validate">
                                        <label for="saldo_inicial_{{ $programa->id }}">R$</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row center section-margins side-margins print-hidden">
                <a class="btn-small black waves-effect waves-light" onclick="history.back()">Voltar</a>
                <button type="submit" class="btn-small green darken-2 waves-effect waves-light">Salvar</button>
            </div>
        </form>
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
