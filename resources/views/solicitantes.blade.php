
@extends('layout')
@section('content')
    <div class='center print-hidden'>
        <h6>Solicitantes</h6>
        <hr>
    </div>
    <div class="print-only section-margins">
        <h6><b>Lista de solicitantes</b></h6>
    </div>
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
