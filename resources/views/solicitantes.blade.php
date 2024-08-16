
@extends('layout')
@section('title', $title)
@section('content')
    <div class='center print-hidden section-margin-bottom'>
        <h5>{{ $title }}</h5>
    </div>
    <div class="print-only section-margins">
        <h6><b>Lista de solicitantes</b></h6>
    </div>
    <form action="#" method="GET" class="print-hidden">
        <div class="row">
            <div class="col s12 m2 input-field">
                <a href="{{ route('import_menu') }}" class="btn green darken-2 waves-effect waves-light">Importar CSV</a>
            </div>
            <div class="col s12 m3 input-field">
                <input type="text" name="search" placeholder="Nome, email, endereço, cidade, etc."> 
            </div>
            <div class="col s4 m3 input-field">
                <select name="tipo_solicitante">
                    <option value="" selected disabled>Tipo de solicitante</option>
                    <option value="Discente">Discente</option>
                    <option value="Docente Colaborador">Docente Colaborador</option>
                    <option value="Docente Permanente">Docente Permanente</option>
                </select>
                <label>Tipo de solicitante</label>
            </div>
            <div class="col s4 m1 input-field">
                <select name="limite_paginacao">
                    <option value="30" selected>30</option>
                    <option value="1000">1000</option>
                </select>
                <label>Itens por página</label>
            </div>
            <div class="col s4 m3 input-field">
                <button class="btn waves-effect waves-light black" type="submit">Buscar</button>
                <button id="print-button" class="btn-flat waves-effect waves-black" type="button">
                    Imprimir
                    <i class="material-icons right">print</i>
                </button>
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
                    <td>
                        <a href="{{ route('site.solicitante.edit', ['id' => $solicitante->id]) }}" class="btn-flat waves-effect"><i class="material-icons tiny">edit</i></a>
                        <a href="{{ route('site.solicitante.show', ['id' => $solicitante->id]) }}" class="hover-underline"><b>{{ Str::upper($solicitante->nome) }}</b></a>
                    </td>
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
        <a class="btn-small black waves-effect waves-light" onclick="history.back()">Voltar</a>
    </div>
@endsection
