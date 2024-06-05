
@extends('layout')
@section('content')
    <div class='center print-hidden'>
        <h6>Solicitantes</h6>
        <hr>
    </div>
    <div class="print-only section-margins">
        <h6><b>Lista de solicitantes</b></h6>
    </div>
    <form action="#" method="GET" class="print-hidden">
        <div class="row">
            <div class="col s12 m2 input-field">
                <a href="{{ route('import_menu') }}" class="btn green darken-2 waves-effect waves-light">Importar CSV</a>
            </div>
            <div class="col s12 m4 input-field">
                <input type="text" name="search" placeholder="Nome, email, endereço, cidade etc"> 
            </div>
            <div class="col s4 m3 input-field">
                <select name="tipo_solicitante">
                    <option value="" selected disabled>Tipo de solicitante</option>
                    <option value="Discente">Discente</option>
                    <option value="Docente Colaborador">Docente Colaborador</option>
                    <option value="Docente Permanente">Docente Permanente</option>
                    </optgroup>
                </select>
                <label>Tipo de solicitante</label>
            </div>
        </div>
        <div class="container center section-margins">
            <button class="btn waves-effect waves-light black" type="submit">Buscar</button>
            <button id="print-button" class="btn-flat waves-effect waves-light">
                Imprimir
                <i class="material-icons right">print</i>
            </button>
        </div>
    </form>
    @if ($count_solicitantes > 0)
        <div class="row print-hidden">
            {!! $search_message !!}
        </div>
    @endif
    <div class="print-hidden">
        {{ $solicitantes->links('common/pagination') }}
    </div>
    @if (count($solicitantes) > 0)
        <table class="bordered striped responsive-table highlight">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cat.</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitantes as $solicitante)
                <tr>
                    <td><a href="{{ route('site.solicitante', ['id' => $solicitante->id]) }}" class="hover-underline"><b>{{ Str::upper($solicitante->nome) }}</b></a></td>
                    <td>{{ $solicitante->tipo_solicitante }}</td>
                    <td><a href="mailto:{{ $solicitante->email }}" class="hover-underline">{{ $solicitante->email }}</a></td>
                    <td>{{ $solicitante->telefone }}</td>
                    <td>{{ $solicitante->endereco_completo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="container center">
            <h6><p>Nenhum solicitante encontrado.</p></h6>
        </div>
    @endif
    <div class="print-hidden">
        {{ $solicitantes->links('common/pagination') }}
    </div>
    <div class="row center section-margins side-margins print-hidden">
        <a class="btn-small black waves-effect waves-black" onclick="history.back()">Voltar</a>
    </div>
@endsection
